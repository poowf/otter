<template>
    <div>
        <div class="row row-cards row-deck">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ prettyResourceName }}</h3>
                        <div class="action-container">
                            <a data-toggle="tooltip" data-original-title="Create" class="icon" v-bind:href="`/otter/${resourceName}/create`"><i class="fe fe-plus"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap">
                            <thead>
                                <tr>
                                    <th v-for="tableType, tableKey in resourceFields">{{ tableKey | sanitize }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="resource, index in resourceData">
                                    <td v-for="fieldType, fieldKey in resourceFields">{{ resource[`${fieldKey}`] }}</td>
                                    <td class="text-right">
                                        <a class="btn btn-secondary btn-sm" v-bind:href="`/otter/${resourceName}/${resource.route_key}/`">View</a>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">Action</button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" v-bind:href="`/otter/${resourceName}/${resource.route_key}/edit/`">
                                                    <i class="fe fe-edit mr-3"></i>Edit
                                                </a>
                                                <button class="btn dropdown-item" @click.stop="handleAction('Delete', resource.route_key)">
                                                    <i class="fe fe-delete mr-3"></i>Delete
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <modal-component :id="`modal-confirmation`" :title="modal.title" :action="modal.action" :visible="modal.visible" @close="resetModal()">
            <div slot="body">
                <p class="">{{ modal.action }} Resource?</p>
            </div>
            <div slot="footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="handleDelete()">{{ modal.action }}</button>
            </div>
        </modal-component>
    </div>
</template>

<script>
    export default {
        name: "TableComponent",
        props: [
            'prettyResourceName',
            'resourceName',
            'resourceFields'
        ],
        data() {
            return {
                loading: false,
                resourceData: [],
                currentSelectedResource: null,
                modal: {
                    title: null,
                    action: null,
                    visible: false,
                },
            }
        },
        created() {
            this.fetchResourceIndex();
        },
        mounted() {
        },
        methods: {
            fetchResourceIndex() {
                axios.get(`/api/otter/${this.resourceName}`)
                    .then(response=>{
                        this.resourceData = response.data.data;
                    })
                    .catch(e => {
                        this.error = `Could not retrieve ${this.resourceName}. Server error.`;
                    })
                    .finally(() => {
                    });
            },
            handleAction(action, resourceKey) {
                this.modal.title = action + ' Confirmation';
                this.modal.action = action;
                this.modal.visible = true;
                this.currentSelectedResource = resourceKey;
            },
            handleDelete() {
                axios.delete(`/api/otter/${this.resourceName}/${this.currentSelectedResource}`)
                    .then(response => {
                        console.log("success");
                        this.fetchResourceIndex();
                    })
                    .catch(e => {
                        this.error = 'Could not update. Please check your values or try again later.';
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            resetModal() {
                this.modal.visible = false;
                this.currentSelectedResource = null;
            },
        }
    }
</script>