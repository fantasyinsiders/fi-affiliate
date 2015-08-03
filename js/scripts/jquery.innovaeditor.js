/***********************************************************
JQuery Wrapper for InnovaStudio Live Editor
© 2012, InnovaStudio (www.innovastudio.com). All rights reserved.

Files to include:

    <script src="scripts/common/jquery-1.7.min.js" type="text/javascript"></script>
    <script src="scripts/innovaeditor.js" type="text/javascript"></script>
    <script src="scripts/jquery.innovaeditor.js" type="text/javascript"></script>

Usage:

    $(document).ready(function () {
        $('#content').liveEdit({
            height: 350,
            css: ['styles/default.css'] ,
            groups: [
                    ["group1", "", ["Bold", "Italic", "Underline", "ForeColor", "RemoveFormat"]],
                    ["group2", "", ["Bullets", "Numbering", "Indent", "Outdent"]],
                    ["group3", "", ["Paragraph", "FontSize", "FontDialog", "TextDialog"]],
                    ["group4", "", ["LinkDialog", "ImageDialog", "TableDialog", "Emoticons", "Snippets"]],
                    ["group5", "", ["Undo", "Redo", "FullScreen", "SourceDialog"]]
        });

        $('#content').data('liveEdit').startedit();
    });

To get the content:

    $('#content').data('liveEdit').getXHTMLBody(); 

************************************************************/
var _editorobj = new InnovaEditor("_editorobj");
var _active_content_id = '';

; (function ($) {

    $.liveEdit = function (element, options) {

        var defaults = {
            preview_id: '',
            modal_id: '',
            width: '100%',
            height: 530,
            css: '',
            arrCustomButtons: [],/*[["Snippets", "modalDialog('bootstrap/snippets.htm',900,658,'Insert Snippets');", "Snippets", "btnContentBlock.gif"]]*/
            toolbarMode: 3,
            groups: [
                    ["group1", "", ["Bold", "Italic", "Underline", "ForeColor", "RemoveFormat"]],
                    ["group2", "", ["Bullets", "Numbering", "Indent", "Outdent"]],
                    ["group3", "", ["Paragraph", "FontSize", "FontDialog", "TextDialog"]],
                    ["group4", "", ["LinkDialog", "ImageDialog", "YoutubeDialog", "TableDialog", "Emoticons"]],
                    ["group5", "", ["Undo", "Redo", "FullScreen", "SourceDialog"]]
                    ],
            enableFlickr: false,
            enableCssButtons: false,
            enableLightbox: false,
            enableTableAutoformat: false,
            returnKeyMode: 3,
            fileBrowser: '',
            arrCustomTag: [["First Name", "[%FIRSTNAME%]"], ["Last Name", "[%LASTNAME%]"], ["Email", "[%EMAIL%]"]],
            pasteTextOnCtrlV: false,
            mode: 'XHTMLBody',
            enableTableAutoformat: true,
            onFoo: function () { }
        }
       
        var plugin = this;

        plugin.settings = {}

        var $element = $(element),
             element = element;

        var content_id = $(element).attr('id');

        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
          
            $("<textarea></textarea>").attr('id', '__hiddentext').attr('style', 'display:none').appendTo('body'); /* Create a hidden textarea */
            _editorobj.width = plugin.settings.width;
            _editorobj.height = plugin.settings.height;
            _editorobj.arrCustomButtons = plugin.settings.arrCustomButtons;
            _editorobj.toolbarMode = plugin.settings.toolbarMode;
            _editorobj.groups = plugin.settings.groups;
            _editorobj.enableFlickr = plugin.settings.enableFlickr;
            _editorobj.enableCssButtons = plugin.settings.enableCssButtons;
            _editorobj.enableLightbox = plugin.settings.enableLightbox;
            _editorobj.enableTableAutoformat = plugin.settings.enableTableAutoformat;
            _editorobj.returnKeyMode = plugin.settings.returnKeyMode;
            _editorobj.css = plugin.settings.css;
            _editorobj.fileBrowser = plugin.settings.fileBrowser;
            _editorobj.arrCustomTag = plugin.settings.arrCustomTag;            
            _editorobj.pasteTextOnCtrlV = plugin.settings.pasteTextOnCtrlV;
            _editorobj.mode = plugin.settings.mode;
            _editorobj.enableTableAutoformat = plugin.settings.enableTableAutoformat;

        }

        plugin.insertHTML = function (sHTML) {
            _editorobj.insertHTML(sHTML);
        }
        plugin.putHTML = function (sHTML) {
            _editorobj.putHTML(sHTML);
        }
        plugin.loadHTML = function (sHTML) {
            _editorobj.loadHTML(sHTML);
        }
        plugin.getXHTML = function (sHTML) {
            return _editorobj.getXHTML();
        }
        plugin.getXHTMLBody = function (sHTML) {
            return _editorobj.getXHTMLBody();
        }
        /*plugin.loadSpecialCharCode = function (arr) {
            _editorobj.loadSpecialCharCode(arr);
        }*/
        plugin.startedit = function () {

            var sHTML = '';         
            var sTagName = $('#' + content_id).prop("tagName").toLowerCase();

            if(plugin.settings.modal_id!=''){

                if(sTagName=="textarea" || sTagName=="input") {
                    _editorobj.REPLACE(content_id, plugin.settings.modal_id);
                }
                else{
                    sHTML = $('#' + content_id).html();

                    $('#__hiddentext').val(sHTML);                
                    _editorobj.REPLACE('__hiddentext', plugin.settings.modal_id);
                }

                return;
            }

            if(sTagName=="textarea" || sTagName=="input") {

                if(plugin.settings.preview_id!=''){
                    _editorobj.height = $('#' + plugin.settings.preview_id).height() + 110;
                    $('#'+plugin.settings.preview_id).css('display','none')
                }
              
                $('#'+content_id).css('display','none')
                $('#'+content_id).after('<div id="' + content_id + 'dummy"></div>');
                _editorobj.REPLACE(content_id, content_id + 'dummy');

            } else {

                if(_active_content_id!=content_id){
                    //finish other opened editor
                    try{$('#'+_active_content_id).data('liveEdit').finishedit()}catch(e){}

                    _active_content_id=content_id
                }
                _active_content_id=content_id;

                sHTML = $('#' + content_id).html();
                _editorobj.height = $('#' + content_id).height() + 110;

                $('#__hiddentext').val(sHTML);            
                _editorobj.REPLACE('__hiddentext', content_id);
            }
            
        }
        plugin.finishedit = function () {  
        
            var sHTML = _editorobj.getXHTMLBody();  

            var sTagName = $('#' + content_id).prop("tagName").toLowerCase();
            if(sTagName=="textarea" || sTagName=="input") {

                if(plugin.settings.preview_id!=''){
                    $('#'+plugin.settings.preview_id).css('display','block')
                    $('#'+plugin.settings.preview_id).html(sHTML);//Back to the preview

                    $('#' + content_id + 'dummy').remove();//Remove the editor

                    $('#'+content_id).val(sHTML);//Set back to input field
                }

            } else {

                $('#' + content_id).html(sHTML);//Back to the preview, also remove the editor

            }   
           
        }

        var foo_private_method = function () {

        }

        plugin.init();
    }

    $.fn.liveEdit = function (options) {

        return this.each(function () {
            if (undefined == $(this).data('liveEdit')) {
                var plugin = new $.liveEdit(this, options);
                $(this).data('liveEdit', plugin);
            }
        });

    }

})(jQuery);
