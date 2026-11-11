<?php
/** Gestión administrativa aislada: no altera los permisos de las páginas de usuario. */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';

function ca_e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function ca_string($value): string { return is_string($value) ? trim($value) : ''; }
function ca_len(string $value): int { return function_exists('mb_strlen') ? mb_strlen($value,'UTF-8') : strlen($value); }
function ca_id($value): int { $n=filter_var($value,FILTER_VALIDATE_INT,['options'=>['min_range'=>1,'max_range'=>2147483647]]); return $n===false ? 0 : (int)$n; }
function ca_states(): array { return ['Pendiente','Aceptado','Rechazado']; }
function ca_units(): array { return ['gramos','kilogramos','libras','onzas','mililitros','litros','tazas','cucharadas','cucharaditas','pizcas','mililitros (ml)','unidad','unidades','dientes','atados','rodajas','hojas','latas','sobres','ramas']; }
function ca_token(): string {
    if (empty($_SESSION['cookly_admin_csrf'])) $_SESSION['cookly_admin_csrf']=bin2hex(random_bytes(32));
    return $_SESSION['cookly_admin_csrf'];
}
function ca_check_token($value): void {
    if (!is_string($value) || empty($_SESSION['cookly_admin_csrf']) || !hash_equals($_SESSION['cookly_admin_csrf'],$value)) {
        throw new DomainException('La solicitud caducó o no es válida. Recarga la página y vuelve a intentarlo.');
    }
}
function ca_flash(string $message): void { $_SESSION['cookly_admin_flash']=$message; }
function ca_take_flash(): string { $m=$_SESSION['cookly_admin_flash']??''; unset($_SESSION['cookly_admin_flash']); return is_string($m)?$m:''; }
function ca_log(string $message): void {
    // El registro no debe convertir un cambio ya confirmado en un error de guardado.
    try {
        if (function_exists('registrar_log')) registrar_log($message,'INFO'); else error_log('[Cookly Admin] '.$message);
    } catch (Throwable $e) { error_log('[Cookly Admin] No se pudo escribir el registro: '.$e->getMessage()); }
}
function ca_filters(array $input): array {
    $q=ca_string($input['q']??''); if(ca_len($q)>160) $q=function_exists('mb_substr')?mb_substr($q,0,160,'UTF-8'):substr($q,0,160);
    $state=ca_string($input['estado']??''); if(!in_array($state,ca_states(),true)) $state='';
    $order=ca_string($input['orden']??'recientes');if(!in_array($order,['recientes','antiguas','titulo'],true))$order='recientes';
    return ['q'=>$q,'estado'=>$state,'categoria'=>ca_id($input['categoria']??0),'autor'=>ca_id($input['autor']??0),'orden'=>$order,'pagina'=>max(1,ca_id($input['pagina']??1))];
}
function ca_url(array $filters, array $changes=[]): string { return 'admin.php?'.http_build_query(array_merge($filters,$changes),'','&',PHP_QUERY_RFC3986); }
function ca_hidden(array $filters): void { foreach($filters as $k=>$v) echo '<input type="hidden" name="'.ca_e($k).'" value="'.ca_e($v).'">'; }
function ca_catalogs(): array {
    global $pdo;
    return ['categorias'=>$pdo->query('SELECT id_categoria,nombre FROM categorias ORDER BY nombre')->fetchAll(), 'usuarios'=>$pdo->query('SELECT id_usuario,nombre FROM usuarios ORDER BY nombre')->fetchAll()];
}
function ca_statistics(): array {
    global $pdo;
    $stats=['Total'=>0,'Pendiente'=>0,'Aceptado'=>0,'Rechazado'=>0];
    foreach($pdo->query('SELECT estado,COUNT(*) AS cantidad FROM recetas GROUP BY estado')->fetchAll() as $r){$stats['Total']+=(int)$r['cantidad']; if(isset($stats[$r['estado']]))$stats[$r['estado']]=(int)$r['cantidad'];}
    $stats['usuarios']=(int)$pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    $stats['categorias']=(int)$pdo->query('SELECT COUNT(*) FROM categorias')->fetchColumn();
    $stats['por_categoria']=$pdo->query('SELECT c.nombre,COUNT(r.id_receta) AS cantidad FROM categorias c LEFT JOIN recetas r ON r.id_categoria=c.id_categoria GROUP BY c.id_categoria,c.nombre ORDER BY cantidad DESC,c.nombre')->fetchAll();
    $stats['por_dificultad']=$pdo->query('SELECT dificultad,COUNT(*) AS cantidad FROM recetas GROUP BY dificultad ORDER BY cantidad DESC,dificultad')->fetchAll();
    $stats['top']=$pdo->query('SELECT u.nombre,COUNT(r.id_receta) AS cantidad FROM usuarios u LEFT JOIN recetas r ON r.id_usuario=u.id_usuario GROUP BY u.id_usuario,u.nombre ORDER BY cantidad DESC,u.nombre LIMIT 3')->fetchAll();
    return $stats;
}
function ca_listing(array $f): array {
    global $pdo; $where=[];$params=[];
    if($f['q']!==''){$like='%'.str_replace(['!','%','_'],['!!','!%','!_'],$f['q']).'%';$where[]="(r.titulo LIKE ? ESCAPE '!' OR u.nombre LIKE ? ESCAPE '!' OR c.nombre LIKE ? ESCAPE '!')";array_push($params,$like,$like,$like);}
    if($f['estado']!==''){$where[]='r.estado=?';$params[]=$f['estado'];}
    if($f['categoria']){$where[]='r.id_categoria=?';$params[]=$f['categoria'];}
    if($f['autor']){$where[]='r.id_usuario=?';$params[]=$f['autor'];}
    $from=' FROM recetas r INNER JOIN usuarios u ON u.id_usuario=r.id_usuario INNER JOIN categorias c ON c.id_categoria=r.id_categoria';
    $condition=$where?' WHERE '.implode(' AND ',$where):'';
    $s=$pdo->prepare('SELECT COUNT(*)'.$from.$condition);$s->execute($params);$total=(int)$s->fetchColumn();
    $pages=max(1,(int)ceil($total/10));$f['pagina']=min($f['pagina'],$pages);
    $order=['recientes'=>'r.id_receta DESC','antiguas'=>'r.id_receta ASC','titulo'=>'r.titulo ASC,r.id_receta DESC'][$f['orden']];
    $s=$pdo->prepare('SELECT r.*,u.nombre AS autor,c.nombre AS categoria'.$from.$condition.' ORDER BY '.$order.' LIMIT ? OFFSET ?');
    foreach($params as $i=>$v)$s->bindValue($i+1,$v,is_int($v)?PDO::PARAM_INT:PDO::PARAM_STR);
    $s->bindValue(count($params)+1,10,PDO::PARAM_INT);$s->bindValue(count($params)+2,($f['pagina']-1)*10,PDO::PARAM_INT);$s->execute();
    return ['recetas'=>$s->fetchAll(),'total'=>$total,'paginas'=>$pages,'filtros'=>$f];
}
function ca_recipe(int $id, bool $lock=false): ?array {
    global $pdo; $s=$pdo->prepare('SELECT r.*,u.nombre AS autor,c.nombre AS categoria FROM recetas r INNER JOIN usuarios u ON u.id_usuario=r.id_usuario INNER JOIN categorias c ON c.id_categoria=r.id_categoria WHERE r.id_receta=?'.($lock?' FOR UPDATE':''));$s->execute([$id]);return $s->fetch()?:null;
}
function ca_ingredients(int $id, bool $lock=false): array {
    global $pdo;$s=$pdo->prepare('SELECT ri.id_ingrediente,i.nombre,ri.cantidad,ri.unidad_medida FROM receta_ingredientes ri INNER JOIN ingredientes i ON i.id_ingrediente=ri.id_ingrediente WHERE ri.id_receta=? ORDER BY ri.id_ingrediente'.($lock?' FOR UPDATE':''));$s->execute([$id]);return $s->fetchAll();
}
function ca_signature(array $r, ?array $ingredients=null): string {
    $data=[];foreach(['id_receta','id_categoria','id_usuario','titulo','descripcion','instrucciones','tiempo_preparacion','dificultad','porciones','estado','imagen_url'] as $k)$data[$k]=isset($r[$k])?(string)$r[$k]:null;
    if($ingredients!==null){$data['ingredientes']=[];foreach($ingredients as $i)$data['ingredientes'][]=[(string)$i['id_ingrediente'],(string)$i['cantidad'],(string)$i['unidad_medida']];}
    return hash('sha256',json_encode($data,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR));
}
function ca_change_state(int $id,string $state,string $version): void {
    requerirAdmin();global $pdo;if(!$id||!in_array($state,ca_states(),true))throw new InvalidArgumentException('Elige una receta y un estado válidos.');
    try{$pdo->beginTransaction();$r=ca_recipe($id,true);if(!$r)throw new InvalidArgumentException('La receta ya no existe.');
        if(!hash_equals(ca_signature($r),$version))throw new InvalidArgumentException('La receta cambió mientras la revisabas. Recarga antes de modificar su estado.');
        $s=$pdo->prepare('UPDATE recetas SET estado=? WHERE id_receta=?');$s->execute([$state,$id]);$pdo->commit();
    }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();throw $e;}
    ca_log('Administración: receta ID '.$id.' pasó de '.$r['estado'].' a '.$state.'.');
}
function ca_prepare_delete(int $id): array {
    requerirAdmin();$r=ca_recipe($id);if(!$r)throw new InvalidArgumentException('La receta ya no existe.');$ings=ca_ingredients($id);
    $tickets=$_SESSION['cookly_admin_delete']??[];foreach($tickets as $k=>$t)if(($t['vence']??0)<time())unset($tickets[$k]);
    if(count($tickets)>20)$tickets=array_slice($tickets,-20,null,true);
    $nonce=bin2hex(random_bytes(24));$tickets[$nonce]=['id'=>$id,'version'=>ca_signature($r,$ings),'vence'=>time()+900];$_SESSION['cookly_admin_delete']=$tickets;
    return ['receta'=>$r,'cantidad'=>count($ings),'nonce'=>$nonce];
}
function ca_delete(int $id,string $nonce): void {
    requerirAdmin();global $pdo;$ticket=$_SESSION['cookly_admin_delete'][$nonce]??null;
    if(!$ticket||$ticket['id']!==$id||$ticket['vence']<time())throw new InvalidArgumentException('La confirmación caducó. Selecciona Eliminar de nuevo.');
    try{$pdo->beginTransaction();$r=ca_recipe($id,true);if(!$r)throw new InvalidArgumentException('La receta ya no existe.');$ings=ca_ingredients($id,true);
        if(!hash_equals($ticket['version'],ca_signature($r,$ings)))throw new InvalidArgumentException('La receta fue modificada después de abrir la confirmación. Revísala y vuelve a confirmar.');
        $pdo->prepare('DELETE FROM receta_ingredientes WHERE id_receta=?')->execute([$id]);
        $pdo->prepare('DELETE FROM recetas WHERE id_receta=?')->execute([$id]);$pdo->commit();unset($_SESSION['cookly_admin_delete'][$nonce]);
    }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();throw $e;}
    ca_log('Administración: se eliminó la receta ID '.$id.' (autor ID '.$r['id_usuario'].', estado '.$r['estado'].').');
}
function ca_validate_recipe(array $input): array {
    $d=[];foreach(['titulo','descripcion','instrucciones','dificultad','estado','imagen_url'] as $k)$d[$k]=ca_string($input[$k]??'');
    if($d['titulo']===''||ca_len($d['titulo'])>120)throw new InvalidArgumentException('El título debe tener entre 1 y 120 caracteres.');
    if($d['instrucciones']===''||strlen($d['instrucciones'])>60000||strlen($d['descripcion'])>60000)throw new InvalidArgumentException('Escribe la preparación y limita los textos a 60.000 bytes.');
    $d['id_categoria']=ca_id($input['id_categoria']??0);$d['tiempo_preparacion']=ca_id($input['tiempo_preparacion']??0);$d['porciones']=ca_id($input['porciones']??0);
    if(!$d['id_categoria']||!$d['tiempo_preparacion']||!$d['porciones'])throw new InvalidArgumentException('Selecciona una categoría. El tiempo y las porciones deben ser enteros mayores que cero.');
    if(!in_array($d['dificultad'],['Fácil','Media','Difícil'],true)||!in_array($d['estado'],ca_states(),true))throw new InvalidArgumentException('Selecciona una dificultad y un estado válidos.');
    $url=$d['imagen_url'];
    if($url!=='' && !(filter_var($url,FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($url,PHP_URL_SCHEME)??''),['http','https'],true)) && !(preg_match('~^public/IMG/[a-zA-Z0-9_./-]+\.(?:jpe?g|png|gif|webp)$~i',$url)&&!str_contains($url,'..')))throw new InvalidArgumentException('La imagen debe ser una URL http/https o una ruta de imagen dentro de public/IMG.');
    if(strlen($url)>255)throw new InvalidArgumentException('La dirección de la imagen no puede superar 255 caracteres.');
    $names=$input['ing_nombre']??[];$quantities=$input['ing_cantidad']??[];$units=$input['ing_unidad']??[];
    if(!is_array($names)||!is_array($quantities)||!is_array($units)||count($names)>100)throw new InvalidArgumentException('El formulario admite hasta 100 ingredientes.');
    $d['ingredientes']=[];
    foreach($names as $k=>$value){$name=ca_string($value);if($name==='')continue;$quantity=filter_var($quantities[$k]??null,FILTER_VALIDATE_FLOAT);$unit=ca_string($units[$k]??'');
        if(ca_len($name)>80||$quantity===false||$quantity<=0||$quantity>99999999.99||round((float)$quantity,2)<=0||!in_array($unit,ca_units(),true))throw new InvalidArgumentException('Revisa los ingredientes: nombre de hasta 80 caracteres, cantidad positiva y unidad válida.');
        $d['ingredientes'][]=['nombre'=>$name,'cantidad'=>round((float)$quantity,2),'unidad_medida'=>$unit];}
    return $d;
}
function ca_save_recipe(int $id,array $d,string $version,array $files): void {
    requerirAdmin();global $pdo;$uploaded=null;$url=$d['imagen_url'];
    try{
        $file=$files['imagen_file']??null;
        if($file && ($file['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_NO_FILE){
            if(!is_array($file)||($file['error']??-1)!==UPLOAD_ERR_OK||($file['size']??0)>5*1024*1024||!is_string($file['tmp_name']??null)||!is_uploaded_file($file['tmp_name']))throw new InvalidArgumentException('No se pudo cargar la imagen. Usa un archivo de hasta 5 MB.');
            $info=@getimagesize($file['tmp_name']);$ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'][$info['mime']??'']??null;
            if(!$ext)throw new InvalidArgumentException('El archivo debe ser una imagen JPG, PNG, WEBP o GIF válida.');
            $dir=__DIR__.'/../public/IMG/recetas';if(!is_dir($dir)&&!mkdir($dir,0755,true)&&!is_dir($dir))throw new RuntimeException('No se pudo preparar la carpeta de imágenes.');
            $name='admin_'.bin2hex(random_bytes(16)).'.'.$ext;$uploaded=$dir.'/'.$name;
            if(!move_uploaded_file($file['tmp_name'],$uploaded))throw new RuntimeException('No se pudo guardar la imagen.');$url='public/IMG/recetas/'.$name;
        }
        $pdo->beginTransaction();$r=ca_recipe($id,true);if(!$r)throw new InvalidArgumentException('La receta ya no existe.');$ings=ca_ingredients($id,true);
        if(!hash_equals(ca_signature($r,$ings),$version))throw new InvalidArgumentException('Otra persona modificó la receta. Recarga la edición antes de guardar.');
        $s=$pdo->prepare('SELECT 1 FROM categorias WHERE id_categoria=?');$s->execute([$d['id_categoria']]);if(!$s->fetchColumn())throw new InvalidArgumentException('La categoría elegida ya no existe.');
        $s=$pdo->prepare('UPDATE recetas SET id_categoria=?,titulo=?,descripcion=?,instrucciones=?,tiempo_preparacion=?,dificultad=?,porciones=?,estado=?,imagen_url=? WHERE id_receta=?');
        $s->execute([$d['id_categoria'],$d['titulo'],$d['descripcion'],$d['instrucciones'],$d['tiempo_preparacion'],$d['dificultad'],$d['porciones'],$d['estado'],$url,$id]);
        $pdo->prepare('DELETE FROM receta_ingredientes WHERE id_receta=?')->execute([$id]);$seen=[];
        foreach($d['ingredientes'] as $ing){$s=$pdo->prepare('INSERT INTO ingredientes(nombre) VALUES (?) ON DUPLICATE KEY UPDATE id_ingrediente=LAST_INSERT_ID(id_ingrediente)');$s->execute([$ing['nombre']]);$iid=(int)$pdo->lastInsertId();
            if(isset($seen[$iid]))throw new InvalidArgumentException('Un ingrediente está repetido. Combina sus cantidades en una sola fila.');$seen[$iid]=true;
            $pdo->prepare('INSERT INTO receta_ingredientes(id_receta,id_ingrediente,cantidad,unidad_medida) VALUES (?,?,?,?)')->execute([$id,$iid,$ing['cantidad'],$ing['unidad_medida']]);}
        $pdo->commit();
    }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();if($uploaded&&is_file($uploaded))unlink($uploaded);throw $e;}
    ca_log('Administración: se editó la receta ID '.$id.' conservando autor ID '.$r['id_usuario'].'. Estado: '.$d['estado'].'.');
}
