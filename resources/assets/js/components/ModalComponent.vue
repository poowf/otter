<template>
    <div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-labelledby="modalComponent" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer"></slot>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ModalComponent",
        props: [
            'id',
            'title',
            'visible',
        ],
        mounted() {
            const self = this;
            $('#' + this.id).on('hidden.bs.modal', function () {
                self.$emit('close');
            })
        },
        watch: {
            visible() {
                $('#' + this.id).modal(this.visible ? 'show' : 'hide');
            }
        },
    }
</script>

<style scoped>

</style>