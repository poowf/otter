<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ resourceName | beautify }}</h3>
                <div class="card-options">
                    <div class="dropdown card-options-dropdown">
                        <button type="button" class="btn btn-option dropdown-toggle" data-toggle="dropdown"><i class="fe fe-more-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" v-bind:href="`/otter/${resourceName}/${resourceId}/edit/`">
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
                        <p>{{ resourceData[`${fieldKey}`] }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="resourceData['relations']">
            <div v-for="relation, relationKey in resourceData['relations']">
                <div v-if="relation.relationshipType === 'HasMany'">
                    <table-component
                            :resource-name="relation.resourceUrlName"
                            :resource-fields="relation.resourceFields"
                    ></table-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ShowComponent",
        props: [
            'resourceId',
            'resourceName',
            'resourceFields',
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
                axios.get(`/api/otter/${this.resourceName}/${this.resourceId}`)
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