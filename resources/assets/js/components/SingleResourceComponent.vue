<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><span v-if="prettyResourceName">{{ prettyResourceName | beautify }}</span><span v-else>{{ resourceName | beautify }}</span></h3>
                <div class="card-options">
                    <div class="dropdown card-options-dropdown">
                        <button type="button" class="btn btn-option dropdown-toggle" data-toggle="dropdown"><i class="fe fe-more-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-dark dropdown-menu-right">
                            <a class="dropdown-item" v-bind:href="`/${pathPrefix}/${resourceName}/${resourceId}/`">
                                <i class="fe fe-eye mr-3"></i>View
                            </a>
                            <a class="dropdown-item" v-bind:href="`/${pathPrefix}/${resourceName}/${resourceId}/edit/`">
                                <i class="fe fe-edit mr-3"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12" v-for="fieldType, fieldKey in resourceFields">
                        <div class="h6">{{ fieldKey | beautify }}</div>
                        <pre v-if="fieldType=='textarea'">{{ resourceData[`${fieldKey}`] }}</pre>
                        <pre v-else-if="fieldType=='wysiwyg'" v-html="resourceData[`${fieldKey}`]"></pre>
                        <p v-else>{{ resourceData[`${fieldKey}`] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SingleResourceComponent",
        props: [
            'prettyResourceName',
            'resourceId',
            'resourceName',
            'resourceFields',
            'pathPrefix'
        ],
        data() {
            return {
                loading: false,
                resourceData: {},
            }
        },
        created() {
            this.fetchResource();
        },
        mounted() {
        },
        methods: {
            fetchResource() {
                axios.get(`/api/${this.pathPrefix}/${this.resourceName}/${this.resourceId}`)
                    .then(response=>{
                        this.resourceData = response.data.data;
                    })
                    .catch(e => {
                        this.error = `Could not retrieve ${this.resourceName}. Server error.`;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
        }
    }
</script>
