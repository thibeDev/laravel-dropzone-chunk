<dropzone-wrapper
        element-id="media-video"
        upload-url="/admin/video/upload"
        csrf="{{csrf_token()}}"
></dropzone-wrapper>

<dropzone-wrapper
        element-id="media-video-manual-upload"
        upload-url="/admin/video/manualUpload"
        csrf="{{csrf_token()}}"
        param-name="mySpecialInputName"
></dropzone-wrapper>