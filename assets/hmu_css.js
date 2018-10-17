jQuery(document).ready( function($){

    var updateCSS = function(){ $("#hmu_css").val( editor.getSession().getValue() ); }
    $(".hmu-general-form").submit( updateCSS );

});

var editor = ace.edit("customCss");
editor.setTheme("ace/theme/monokai");
editor.getSession().setMode("ace/mode/css");