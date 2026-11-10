#!/usr/bin/env python3
"""Genera el PDF desde el mismo contenido y las capturas del manual web.
Uso: python tools/manual/generar_pdf.py [ruta_de_salida.pdf]
Dependencias de desarrollo: reportlab y Pillow. No se necesitan en el servidor PHP.
"""
from pathlib import Path
import json,sys
from html import escape
from PIL import Image as PILImage
from reportlab.pdfgen.canvas import Canvas
from reportlab.platypus import (BaseDocTemplate, PageTemplate, Frame, Paragraph, Spacer, PageBreak, Table, TableStyle, Image, KeepTogether, NextPageTemplate)
from reportlab.platypus.tableofcontents import TableOfContents
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib import colors
from reportlab.lib.enums import TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.utils import ImageReader
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
ROOT=Path(__file__).resolve().parents[2]
OUTPUT=Path(sys.argv[1]).resolve() if len(sys.argv)>1 else ROOT/'public/docs/Manual_de_Usuario_Cookly.pdf'
OUTPUT.parent.mkdir(parents=True,exist_ok=True)
data=json.loads((ROOT/'includes/manual_contenido.json').read_text(encoding='utf-8'))
for name,file in [('Nunito','Nunito-Regular.ttf'),('NunitoBold','Nunito-ExtraBold.ttf')]:
    pdfmetrics.registerFont(TTFont(name,str(ROOT/'public/fonts'/file)))
pdfmetrics.registerFontFamily('Nunito',normal='Nunito',bold='NunitoBold',italic='Nunito',boldItalic='NunitoBold')
GREEN=colors.HexColor('#0F766E');DARK=colors.HexColor('#115E59');INK=colors.HexColor('#1F2937');MUTED=colors.HexColor('#5B6776');LINE=colors.HexColor('#DDE5E8');LIGHT=colors.HexColor('#F0FDFA')
PAGE_W,PAGE_H=A4; MARGIN=43; WIDTH=PAGE_W-2*MARGIN
styles={}
for key,size,leading,font,color in [('body',10.2,15.6,'Nunito',INK),('lead',11,17,'Nunito',MUTED),('h1',24,29,'NunitoBold',DARK),('h2',12,17,'NunitoBold',DARK),('caption',8.3,12,'Nunito',MUTED),('cell',9.2,13.5,'Nunito',INK),('th',9.2,13,'NunitoBold',DARK),('eyebrow',8,12,'NunitoBold',GREEN)]:
    styles[key]=ParagraphStyle(key,fontName=font,fontSize=size,leading=leading,textColor=color,spaceAfter=8,splitLongWords=True,allowWidows=0,allowOrphans=0)
styles['h1'].spaceAfter=15;styles['h1'].keepWithNext=True;styles['h2'].keepWithNext=True;styles['h2'].spaceBefore=9
styles['eyebrow'].keepWithNext=True
styles['caption'].spaceAfter=13

def clean(text):return escape(str(text)).replace('\n','<br/>')
def p(text,style='body'):return Paragraph(clean(text),styles[style])

class ManualDoc(BaseDocTemplate):
    def afterFlowable(self,flow):
        if getattr(flow,'chapter_key',None):
            key=flow.chapter_key;title=flow.chapter_title
            self.canv.bookmarkPage(key);self.canv.addOutlineEntry(title,key,level=0,closed=False)
            self.notify('TOCEntry',(0,title,self.page,key))

class CountCanvas(Canvas):
    # Page numbers are drawn after the final layout pass by the document itself.
    pass

def cover(canvas,doc):
    canvas.saveState();canvas.setFillColor(DARK);canvas.rect(0,0,PAGE_W,PAGE_H,fill=1,stroke=0)
    canvas.setFillColor(colors.HexColor('#0F766E'));canvas.circle(PAGE_W-30,PAGE_H-60,210,fill=1,stroke=0)
    canvas.setFillColor(colors.white);canvas.roundRect(MARGIN,PAGE_H-220,140,140,24,fill=1,stroke=0)
    canvas.drawImage(ImageReader(str(ROOT/'public/IMG/logo.png')),MARGIN+9,PAGE_H-211,width=122,height=122,mask='auto',preserveAspectRatio=True,anchor='c')
    canvas.setFont('NunitoBold',10);canvas.setFillColor(colors.HexColor('#99F6E4'));canvas.drawString(MARGIN,PAGE_H-265,'COOKLY / GUÍA DE LA PLATAFORMA')
    canvas.setFillColor(colors.white);canvas.setFont('NunitoBold',41);canvas.drawString(MARGIN,PAGE_H-330,'Manual de');canvas.drawString(MARGIN,PAGE_H-380,'Usuario')
    canvas.setFont('Nunito',21);canvas.drawString(MARGIN,PAGE_H-430,'Tu recetario, paso a paso.')
    canvas.setStrokeColor(colors.HexColor('#5EEAD4'));canvas.line(MARGIN,250,PAGE_W-MARGIN,250)
    canvas.setFont('Nunito',11);canvas.setFillColor(colors.HexColor('#CCFBF1'))
    for i,line in enumerate(['Edición '+data['version']+' del manual',data['fecha_visible'],'Autor: '+data['autor'],'Capturas reales con datos de prueba']):canvas.drawString(MARGIN,220-i*23,line)
    canvas.setFont('Nunito',9);canvas.drawString(MARGIN,65,'Crear · Organizar · Compartir');canvas.restoreState()

def body_page(canvas,doc):
    canvas.saveState();canvas.setFillColor(GREEN);canvas.rect(0,PAGE_H-7,PAGE_W,7,fill=1,stroke=0)
    canvas.setFont('NunitoBold',9);canvas.setFillColor(GREEN);canvas.drawString(MARGIN,PAGE_H-30,'Cookly')
    canvas.setFont('Nunito',8);canvas.setFillColor(MUTED);canvas.drawRightString(PAGE_W-MARGIN,PAGE_H-30,'Manual de Usuario · Edición '+data['version'])
    canvas.setStrokeColor(LINE);canvas.line(MARGIN,38,PAGE_W-MARGIN,38)
    canvas.setFont('Nunito',8);canvas.drawString(MARGIN,24,'Equipo Cookly · '+data['fecha_visible']);canvas.drawRightString(PAGE_W-MARGIN,24,str(doc.page));canvas.restoreState()

doc=ManualDoc(str(OUTPUT),pagesize=A4,leftMargin=MARGIN,rightMargin=MARGIN,topMargin=56,bottomMargin=52,title='Cookly - Manual de Usuario',author='Equipo Cookly',subject='Guía de las funcionalidades verificadas y capturas de la aplicación',pageCompression=1)
frame=Frame(MARGIN,52,WIDTH,PAGE_H-108,id='body',leftPadding=0,bottomPadding=0,rightPadding=0,topPadding=0)
doc.addPageTemplates([PageTemplate(id='cover',frames=[frame],onPage=cover),PageTemplate(id='body',frames=[frame],onPage=body_page)])
story=[Spacer(1,1),NextPageTemplate('body'),PageBreak()]
story+=[p('CONTENIDO','eyebrow'),p('Encuentra lo que necesitas','h1'),p('Haz clic en un capítulo del índice para ir directamente a él. También puedes usar los marcadores del lector PDF.','lead')]
toc=TableOfContents();toc.levelStyles=[ParagraphStyle('toc',fontName='Nunito',fontSize=10.3,leading=15.5,textColor=INK,leftIndent=0,rightIndent=20,firstLineIndent=0,spaceBefore=0)]
toc.tableStyle=TableStyle([('TOPPADDING',(0,0),(-1,-1),2),('BOTTOMPADDING',(0,0),(-1,-1),2),('LEFTPADDING',(0,0),(-1,-1),0),('RIGHTPADDING',(0,0),(-1,-1),0)])
story.append(toc)
story+=[Spacer(1,18),p('Todas las imágenes proceden de la aplicación ejecutada localmente. Los datos visibles son de demostración. No se utilizó la base de datos de producción.','caption')]
figure=0
for number,ch in enumerate(data['capitulos'],1):
    story.append(PageBreak());story.append(p(ch['grupo'].upper(),'eyebrow'))
    heading=p(f'{number:02d}  {ch["titulo"]}','h1');heading.chapter_key=ch['id'];heading.chapter_title=f'{number:02d}. {ch["titulo"]}';story.append(heading)
    story.append(p(ch['intro'],'lead'))
    for block_index,block in enumerate(ch['bloques']):
        items=[]
        typ=block['tipo']
        if typ in ['paso','texto','pregunta']:
            if block.get('titulo'):items.append(p(block['titulo'],'h2'))
            items.append(p(block['texto']))
        elif typ=='nota':
            warning=block.get('nivel')=='aviso';bg=colors.HexColor('#FFFBEB') if warning else LIGHT
            cell=[p(block['titulo'],'h2'),p(block['texto'])]
            note=Table([[cell]],colWidths=[WIDTH],hAlign='LEFT')
            note.setStyle(TableStyle([('BACKGROUND',(0,0),(-1,-1),bg),('LINEBEFORE',(0,0),(0,-1),3,colors.HexColor('#D97706') if warning else GREEN),('LEFTPADDING',(0,0),(-1,-1),13),('RIGHTPADDING',(0,0),(-1,-1),12),('TOPPADDING',(0,0),(-1,-1),5),('BOTTOMPADDING',(0,0),(-1,-1),4)]))
            items.extend([Spacer(1,7),note,Spacer(1,10)])
        elif typ=='tabla':
            items.append(p(block['titulo'],'h2'))
            widths=[WIDTH*.3,WIDTH*.7] if len(block['columnas'])==2 else [WIDTH/len(block['columnas'])]*len(block['columnas'])
            rows=[[p(c,'th') for c in block['columnas']]]+[[p(c,'cell') for c in row] for row in block['filas']]
            table=Table(rows,colWidths=widths,repeatRows=1,hAlign='LEFT')
            table.setStyle(TableStyle([('BACKGROUND',(0,0),(-1,0),LIGHT),('VALIGN',(0,0),(-1,-1),'TOP'),('LEFTPADDING',(0,0),(-1,-1),8),('RIGHTPADDING',(0,0),(-1,-1),8),('TOPPADDING',(0,0),(-1,-1),7),('BOTTOMPADDING',(0,0),(-1,-1),5),('LINEBELOW',(0,0),(-1,-1),.5,LINE)]))
            items.extend([table,Spacer(1,10)])
        if block.get('imagen'):
            figure+=1;image=data['imagenes'][block['imagen']];file=ROOT/'public/IMG/manual'/image['archivo']
            with PILImage.open(file) as im:w,h=im.size
            max_height=280
            factor=min(WIDTH/w,max_height/h)
            screenshot=Image(str(file),width=w*factor,height=h*factor);screenshot.hAlign='CENTER'
            caption=p(f'Figura {figure}. {image["descripcion"]}','caption')
            items.extend([Spacer(1,7),screenshot,Spacer(1,6),caption])

        flow = KeepTogether(items) if typ != 'tabla' else None
        if flow is not None:
            if typ == 'nota' and block_index == len(ch['bloques'])-1 and story and isinstance(story[-1],KeepTogether):
                story.append(KeepTogether(story.pop()._content + items))
            else: story.append(flow)
        else: story.extend(items)

doc.multiBuild(story,canvasmaker=CountCanvas)
print(json.dumps({'pdf':str(OUTPUT),'bytes':OUTPUT.stat().st_size,'chapters':len(data['capitulos']),'figures':figure},ensure_ascii=False))
