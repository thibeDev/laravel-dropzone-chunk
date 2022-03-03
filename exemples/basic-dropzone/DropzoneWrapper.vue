<template>
    <div>
        <div :id="elementId" class="dropzone videodz">
            <transition name="fade">
                <div v-if="progress >0" class="upload-progress text-white">{{progress}} % uploaded</div>
            </transition>
        </div>
    </div>
</template>

<script>
    /**
     * It needs Dropzone... And you still have to add this component to the components array of Vue
     */
    import { Dropzone } from "dropzone";

    export default {
        name: "DropzoneWrapper",
        props: {
            elementId: {
                required: true,
                type: String
            },
            uploadUrl: {
                required: true,
                type: String
            },
            csrf: {
                required: true,
                type:String
            },
            paramName: {
                required: false,
                default() {
                    return 'file'
                }
            }
        },
        data() {
            return {
                dropzone: null,
                progress: 0
            }
        },
        mounted() {
            this.dropzone = new Dropzone('#'+this.elementId, {
                headers: {
                    'X-CSRF-TOKEN': this.csrf
                },
                paramName: this.paramName,
                autoProcessQueue:true,
                method: "POST",
                maxFilesize: 4000000000,
                chunking: true,
                chunkSize: 5000000,
                retryChunks: true,
                // If true, the individual chunks of a file are being uploaded simultaneously.
                parallelChunkUploads: true,
                url: this.uploadUrl,
            });
            this.dropzone.on('uploadprogress', (file, progress, bytesSent)=> {
                this.progress = progress.toFixed(2)
            })
        }
    }
</script>

<style lang="scss">
    .dropzone.videodz {
        position: relative;
        .upload-progress {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background-color: rgba(255,255,255,.4);
            padding: 0 0.4em;
            border-radius: 3px;
        }
        .dz-progress {
            margin-top: 0;
            left: 5%;
            right: 5%;
        }
        .dz-image {
            display: none;
        }
        &.dropzone .dz-preview {
            position: relative;
            display: block;
            vertical-align: top;
            margin: 16px;
            min-height: 100px;
            .dz-filename {
                position: relative;
                top: 15px;
            }
        }
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>