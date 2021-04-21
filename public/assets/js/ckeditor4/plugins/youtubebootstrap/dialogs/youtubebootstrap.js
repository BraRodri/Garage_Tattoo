/**
 * @license Edit and use as you want
 *
 * Creato da TurboLab.it - 01/01/2014 (buon anno!)
 * Modified by Eric van Eldik
 */
CKEDITOR.dialog.add( 'youtubebootstrapDialog', function( editor ) {

    return {
        title: 'Insertar YouTube',
        minWidth: 400,
        minHeight: 75,
        contents: [
            {
                id: 'tab-basic',
                label: 'Configuración Básica',
                elements: [
                    {
                        type: 'text',
                        id: 'youtubeURL',
                        label: 'Pegar la URL para incluir video de youtube'
                    }
                ]
            }
        ],
        onOk: function() {
            var dialog = this;
			var url=dialog.getValueOf( 'tab-basic', 'youtubeURL').trim();
			var regExURL=/v=([^&$]+)/i;
			var id_video=url.match(regExURL);
			
			if(id_video==null || id_video=='' || id_video[0]=='' || id_video[1]=='')
				{
				alert("La URL dada no es válida, la mayoría se ve así: a\n\n\t http://www.youtube.com/watch?v=abcdef \n\n");
				return false;
				}

            var oTag = editor.document.createElement( 'iframe' );
			            
            var oP = editor.document.createElement( 'p' );
            oP.setAttribute( 'class', 'embed-responsive embed-responsive-16by9');
            oP.setHtml( '<iframe src="//www.youtube.com/embed/' + id_video[1] + '?rel=0"></iframe>' )
 
            editor.insertElement( oP );
        }
    };
});