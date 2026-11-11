<?php
require_once __DIR__.'/includes/auth.php';
requerirAdmin();
require_once __DIR__.'/includes/admin_gestion.php';
if(!in_array($_SERVER['REQUEST_METHOD'],['GET','POST'],true)){http_response_code(405);header('Allow: GET, POST');exit('Método no permitido.');}
$filtros=ca_filters($_SERVER['REQUEST_METHOD']==='POST'?$_POST:$_GET);
$error='';$confirmacion=null;$flash=ca_take_flash();
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        ca_check_token($_POST['_csrf']??null);
        $id=ca_id($_POST['id_receta']??0);$accion=ca_string($_POST['accion']??'');
        if($accion==='estado'){
            ca_change_state($id,ca_string($_POST['nuevo_estado']??''),ca_string($_POST['version']??''));
            ca_flash('El estado de la receta se actualizó correctamente.');header('Location: '.ca_url($filtros),true,303);exit;
        }elseif($accion==='solicitar_eliminacion'){
            $confirmacion=ca_prepare_delete($id);
        }elseif($accion==='eliminar'){
            ca_delete($id,ca_string($_POST['confirmacion']??''));ca_flash('La receta se eliminó correctamente.');header('Location: '.ca_url($filtros),true,303);exit;
        }else throw new InvalidArgumentException('La acción no es válida.');
    }catch(DomainException $e){http_response_code(403);$error=$e->getMessage();}
    catch(InvalidArgumentException $e){http_response_code(400);$error=$e->getMessage();}
    catch(Throwable $e){http_response_code(500);error_log('[Cookly Admin] '.$e->getMessage());$error='No se pudo completar la operación. El cambio no se ha confirmado; vuelve a intentarlo o consulta al responsable.';}
}
$stats=ca_statistics();$catalogos=ca_catalogs();$listado=ca_listing($filtros);$filtros=$listado['filtros'];
$titulo='Administrar recetas';$estilosPagina=['public/css/admin.css'];$scriptsPagina=['public/JS/admin.js'];$claseBody='cookly-admin-body';
require __DIR__.'/includes/header.php';
?>
<div class="ca-root">
    <div class="ca-breadcrumb">COOKLY <span>/</span> ADMINISTRACIÓN</div>
    <header class="ca-hero">
        <div><p class="ca-eyebrow">TU COMUNIDAD, EN ORDEN</p><h1>Todas las recetas<span>.</span></h1><p>Revisa y administra las preparaciones de toda la comunidad, en cualquier estado.</p></div>
        <div class="ca-hero-actions"><a class="ca-button" href="#reportes"><?= cookly_nav_icon('chart') ?>Reportes</a><a class="ca-button ca-primary" href="receta_crear.php"><?= cookly_nav_icon('plus') ?>Nueva receta</a></div>
    </header>
    <?php if($flash): ?><div class="ca-alert ca-alert-success" role="status"><?= ca_e($flash) ?></div><?php endif; ?>
    <?php if($error): ?><div class="ca-alert ca-alert-error" role="alert"><?= ca_e($error) ?></div><?php endif; ?>
    <?php if($confirmacion): $r=$confirmacion['receta']; ?>
    <section class="ca-confirm" aria-labelledby="ca-confirm-title">
        <div class="ca-confirm-icon" aria-hidden="true">!</div><div><p class="ca-eyebrow">CONFIRMACIÓN DE ELIMINACIÓN</p><h2 id="ca-confirm-title">¿Eliminar «<?= ca_e($r['titulo']) ?>»?</h2><p>Autor: <strong><?= ca_e($r['autor']) ?></strong> · Estado: <strong><?= ca_e($r['estado']) ?></strong>.</p><p>Se borrará esta receta y sus <?= $confirmacion['cantidad'] ?> registros de ingredientes. No hay papelera ni botón para deshacer esta acción.</p>
        <div class="ca-confirm-actions"><a class="ca-button" href="<?= ca_e(ca_url($filtros)) ?>">Cancelar y conservar</a><form method="post"><?php ca_hidden($filtros); ?><input type="hidden" name="_csrf" value="<?= ca_e(ca_token()) ?>"><input type="hidden" name="id_receta" value="<?= (int)$r['id_receta'] ?>"><input type="hidden" name="accion" value="eliminar"><input type="hidden" name="confirmacion" value="<?= ca_e($confirmacion['nonce']) ?>"><button type="submit" class="ca-button ca-danger">Eliminar definitivamente</button></form></div></div>
    </section>
    <?php endif; ?>
    <section class="ca-summary" aria-label="Resumen de recetas">
    <?php foreach([['Total','Todas las recetas','all'],['Pendiente','Pendientes','pending'],['Aceptado','Aceptadas','accepted'],['Rechazado','Rechazadas','rejected']] as [$key,$label,$kind]): ?>
        <a class="ca-stat ca-stat-<?= $kind ?>" href="<?= ca_e(ca_url($filtros,['estado'=>$key==='Total'?'':$key,'pagina'=>1])) ?>" aria-label="<?= ca_e($label) ?>: <?= $stats[$key] ?>"><span class="ca-stat-dot"></span><span><?= $label ?></span><strong><?= $stats[$key] ?></strong><small><?= $key==='Total'?'En toda la plataforma':($key==='Pendiente'?'Esperan tu revisión':($key==='Aceptado'?'Visibles en Explorar':'Fuera de Explorar')) ?></small></a>
    <?php endforeach; ?>
    </section>
    <section class="ca-library" aria-labelledby="ca-list-title" id="recetas">
        <div class="ca-library-heading"><div><h2 id="ca-list-title">Biblioteca de recetas <span><?= $listado['total'] ?></span></h2><p>Consulta, edita o retira una receta aunque ya la hayas aprobado.</p></div><span class="ca-live-label"><i></i>Administración completa</span></div>
        <form method="get" class="ca-filters" role="search" aria-label="Filtrar recetas">
            <label class="ca-search">Buscar recetas<div><?= cookly_nav_icon('search') ?><input type="search" name="q" value="<?= ca_e($filtros['q']) ?>" placeholder="Título, autor o categoría" maxlength="160"></div></label>
            <label>Estado<select name="estado"><option value="">Todos los estados</option><?php foreach(ca_states() as $state): ?><option value="<?= ca_e($state) ?>" <?= $state===$filtros['estado']?'selected':'' ?>><?= ca_e($state) ?></option><?php endforeach; ?></select></label>
            <label>Categoría<select name="categoria"><option value="0">Todas las categorías</option><?php foreach($catalogos['categorias'] as $c): ?><option value="<?= (int)$c['id_categoria'] ?>" <?= (int)$c['id_categoria']===$filtros['categoria']?'selected':'' ?>><?= ca_e($c['nombre']) ?></option><?php endforeach; ?></select></label>
            <button class="ca-button ca-primary" type="submit">Buscar</button><a class="ca-clear" href="admin.php">Limpiar filtros</a>
            <details class="ca-advanced" <?= $filtros['autor']||$filtros['orden']!=='recientes'?'open':'' ?>><summary>Más opciones</summary><div><label>Autor<select name="autor"><option value="0">Todos los autores</option><?php foreach($catalogos['usuarios'] as $u): ?><option value="<?= (int)$u['id_usuario'] ?>" <?= (int)$u['id_usuario']===$filtros['autor']?'selected':'' ?>><?= ca_e($u['nombre']) ?></option><?php endforeach; ?></select></label><label>Orden<select name="orden"><?php foreach(['recientes'=>'Más recientes','antiguas'=>'Más antiguas','titulo'=>'Título A–Z'] as $key=>$label): ?><option value="<?= $key ?>" <?= $key===$filtros['orden']?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></label></div></details>
        </form>
        <?php if(!$listado['recetas']): ?>
        <div class="ca-empty"><div aria-hidden="true"><?= cookly_nav_icon('search') ?></div><h3>No encontramos recetas con esos filtros</h3><p>Prueba con otro título, estado o autor.</p><a class="ca-button" href="admin.php">Ver todas las recetas</a></div>
        <?php else: ?>
        <p class="ca-table-hint">Desliza la tabla hacia la izquierda para ver el estado y las acciones de cada receta.</p>
        <div class="ca-table-wrap" role="region" aria-label="Listado de recetas" tabindex="0"><table class="ca-table"><thead><tr><th scope="col">Receta</th><th scope="col">Autor</th><th scope="col">Estado</th><th scope="col">Acciones</th></tr></thead><tbody>
        <?php foreach($listado['recetas'] as $r): $stateClass=['Pendiente'=>'pending','Aceptado'=>'accepted','Rechazado'=>'rejected'][$r['estado']]??'pending'; ?>
        <tr data-recipe-id="<?= (int)$r['id_receta'] ?>">
            <td><div class="ca-recipe"><div class="ca-thumbnail"><?php if($r['imagen_url']): ?><img src="<?= ca_e($r['imagen_url']) ?>" alt="<?= ca_e($r['titulo']) ?>" width="64" height="64" loading="lazy"><?php else: ?><?= cookly_nav_icon('book') ?><?php endif; ?></div><div><a class="ca-recipe-title" href="receta_detalle.php?id=<?= (int)$r['id_receta'] ?>"><?= ca_e($r['titulo']) ?></a><p><?= ca_e($r['categoria']) ?><span>·</span><?= (int)$r['tiempo_preparacion'] ?> min<span>·</span>#<?= (int)$r['id_receta'] ?></p></div></div></td>
            <td><span class="ca-author"><?= ca_e($r['autor']) ?></span></td>
            <td><form method="post" class="ca-state-form"><?php ca_hidden($filtros); ?><input type="hidden" name="_csrf" value="<?= ca_e(ca_token()) ?>"><input type="hidden" name="id_receta" value="<?= (int)$r['id_receta'] ?>"><input type="hidden" name="accion" value="estado"><input type="hidden" name="version" value="<?= ca_e(ca_signature($r)) ?>"><select name="nuevo_estado" class="ca-state ca-state-<?= $stateClass ?>" aria-label="Estado de <?= ca_e($r['titulo']) ?>"><?php foreach(ca_states() as $state): ?><option value="<?= ca_e($state) ?>" <?= $r['estado']===$state?'selected':'' ?>><?= ca_e($state) ?></option><?php endforeach; ?></select><button type="submit" class="ca-state-save" aria-label="Guardar estado de <?= ca_e($r['titulo']) ?>">Guardar</button></form></td>
            <td><div class="ca-row-actions"><a href="receta_detalle.php?id=<?= (int)$r['id_receta'] ?>">Ver</a><a href="admin_receta_editar.php?id=<?= (int)$r['id_receta'] ?>">Editar</a><form method="post"><?php ca_hidden($filtros); ?><input type="hidden" name="_csrf" value="<?= ca_e(ca_token()) ?>"><input type="hidden" name="id_receta" value="<?= (int)$r['id_receta'] ?>"><input type="hidden" name="accion" value="solicitar_eliminacion"><button type="submit" class="ca-delete-start">Eliminar</button></form></div></td>
        </tr>
        <?php endforeach; ?>
        </tbody></table></div>
        <nav class="ca-pagination" aria-label="Páginas de recetas"><span>Mostrando <?= ($filtros['pagina']-1)*10+1 ?>–<?= min($filtros['pagina']*10,$listado['total']) ?> de <?= $listado['total'] ?></span><div><?php if($filtros['pagina']>1): ?><a class="ca-button" href="<?= ca_e(ca_url($filtros,['pagina'=>$filtros['pagina']-1])) ?>">← Anterior</a><?php endif; ?><span>Página <?= $filtros['pagina'] ?> de <?= $listado['paginas'] ?></span><?php if($filtros['pagina']<$listado['paginas']): ?><a class="ca-button" href="<?= ca_e(ca_url($filtros,['pagina'=>$filtros['pagina']+1])) ?>">Siguiente →</a><?php endif; ?></div></nav>
        <?php endif; ?>
    </section>
    <details class="ca-reports" id="reportes"><summary><div><?= cookly_nav_icon('chart') ?><span><strong>Reportes de la comunidad</strong><small>Estadísticas por categoría, dificultad y autor</small></span></div><span class="ca-reports-toggle">Ver resumen <?= cookly_nav_icon('chevron') ?></span></summary><div class="ca-reports-content">
        <div class="ca-report-heading"><div><h2>Resumen general</h2><p><?= $stats['usuarios'] ?> usuarios · <?= $stats['categorias'] ?> categorías · <?= $stats['Total'] ?> recetas</p></div><div class="ca-report-actions"><?php if(is_file(__DIR__.'/exportar_excel.php')): ?><a class="ca-button" href="exportar_excel.php">Descargar Excel</a><?php endif; ?><button class="ca-button" type="button" id="ca-print" hidden>Guardar PDF</button><?php if(is_file(__DIR__.'/reportes.php')): ?><a class="ca-button" href="reportes.php">Reportes completos</a><?php endif; ?></div></div>
        <div class="ca-report-grid"><?php foreach([['por_categoria','Recetas por categoría'],['por_dificultad','Recetas por dificultad'],['top','Autores con más recetas']] as [$key,$label]): ?><section><h3><?= $label ?></h3><?php if(!$stats[$key]): ?><p>Aún no hay datos.</p><?php else: foreach($stats[$key] as $row): $pct=$stats['Total']?round((int)$row['cantidad']/$stats['Total']*100,1):0; ?><div class="ca-bar-label"><span><?= ca_e($row['nombre']??$row['dificultad']) ?></span><strong><?= (int)$row['cantidad'] ?></strong></div><div class="ca-bar" role="img" aria-label="<?= ca_e($row['nombre']??$row['dificultad']) ?>: <?= (int)$row['cantidad'] ?> recetas"><span style="width:<?= $pct ?>%"></span></div><?php endforeach; endif; ?></section><?php endforeach; ?></div>
    </div></details>
    <p class="ca-footnote"><?= cookly_nav_icon('shield') ?>Las acciones de este panel están disponibles únicamente para administradores.</p>
</div>
<?php require __DIR__.'/includes/footer.php'; ?>
