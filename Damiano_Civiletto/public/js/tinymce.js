tinymce.init({
    selector: 'textarea.editor',
    mode: 'exact',
    elements: 'texte, edit_texte',
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_resizing : "true",
    theme_advanced_resize_horizontal : "true",
    mobile: {
        theme: 'mobile',
        plugins: 'autosave lists autolink autoresize',
        toolbar: 'undo bold italic forecolor styleselect'
    },
    image_class_list: [
        {title: 'fluid', value: 'img-fluid'}
    ],
    plugins: 'autoresize print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
    imagetools_cors_hosts: ['picsum.photos'],
    menubar: 'file edit view insert format tools table help',
    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
    toolbar_sticky: true,
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    media_scripts: [
        {filter: 'http://media1.tinymce.com'},
        {filter: 'http://media2.tinymce.com', width: 100, height: 200} ],
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,


    // without images_upload_url set, Upload tab won't show up
    images_upload_url: 'http://damianociviletto.com/public/index.php?route=upload',

    // override default upload handler to simulate successful upload
    images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;

        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', 'http://damianociviletto.com/public/index.php?route=upload', true);

        xhr.onload = function() {
            var json;

            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }

            json = JSON.parse(xhr.responseText);

            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }

            success(json.location);
        };

        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        formData.append('test','test1');

        xhr.send(formData);
    },
});