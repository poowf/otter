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
                                    <th v-for="tableType, tableKey in resourceFields">{{ tableKey }}</th>
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
                                                <button class="btn dropdown-item" @click="handleDelete(resource.route_key)">
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
    </div>
</template>

<script>
    export default {
        name: "TableComponent",
        props: [
            'prettyResourceName',
            'resourceName',
            'resourceFields',
        ],
        data() {
            return {
                resourceData: [],
            }
        },
        watch: {
            resourceName() {
                console.log("changed");
                // if (this.resourceName) {
                //     this.fetchResourceIndex();
                // }
            }
        },
        created() {
            this.fetchResourceIndex();
        },
        mounted() {
            console.log('Component mounted.')
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        methods: {
            fetchResourceIndex() {
                axios.get(`/api/otter/${this.resourceName}`)
                    .then(response=>{
                        this.resourceData = response.data.data;
                        console.log(this.resourceData);
                    })
                    .catch(e => {
                        this.error = `Could not retrieve ${this.resourceName}. Server error.`;
                    })
                    .finally(() => {
                    });
            },
            handleDelete(resourceId) {
                axios.delete(`/api/otter/${this.resourceName}/${resourceId}`)
                    .then(response => {
                        console.log(response);
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
        }
    }
</script>