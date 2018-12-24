<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ prettyResourceName }}</h3>
                <div class="card-options">
                    <button type="button" class="btn btn-option" data-toggle="tooltip" title="" data-original-title="Edit">
                        <i class="fe fe-edit"></i>
                    </button>
                    <div class="dropdown card-options-dropdown">
                        <button type="button" class="btn btn-option dropdown-toggle" data-toggle="dropdown"><i class="fe fe-more-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" v-bind:href="`/otter/${resourceName}/${resourceId}/edit/`">
                                <i class="fe fe-edit mr-3"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12" v-for="fieldType, fieldKey in resourceFields">
                        <div class="h6">{{ fieldKey }}</div>
                        <p>{{ resourceData[`${fieldKey}`] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ShowComponent",
        props: [
            'prettyResourceName',
            'resourceId',
            'resourceName',
            'resourceFields'
        ],
        data() {
            return {
                resourceData: {},
            }
        },
        watch: {
            resourceName() {
                console.log("changed");
                // if (this.resourceName) {
                //     this.fetchResource();
                // }
            }
        },
        created() {
            this.fetchResource();
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
            fetchResource() {
                axios.get(`/api/otter/${this.resourceName}/${this.resourceId}`)
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
        }
    }
</script>