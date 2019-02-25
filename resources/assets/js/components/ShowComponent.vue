<template>
    <div>
        <single-resource-component
                :resource-name="resourceName"
                :resource-id="resourceId"
                :resource-fields="resourceFields"
                :resource-prefix="resourcePrefix"
        ></single-resource-component>
        <div v-if="resourceData['relations']">
            <div v-for="relation, relationKey in resourceData['relations']">
                <div v-if="relation.relationshipType === 'HasMany'">
                    <table-component
                            relationship="true"
                            :relation="relation"
                            :parent-resource-id="relation.relationshipId"
                            :parent-resource-name="resourceName"
                            :resource-name="relation.resourceName"
                            :resource-fields="relation.resourceFields"
                            :resource-prefix="resourcePrefix"
                    ></table-component>
                </div>
                <div v-if="relation.relationshipType === 'BelongsToMany'">
                    <table-component
                            relationship="true"
                            :relation="relation"
                            :parent-resource-id="relation.resourceId"
                            :parent-resource-name="resourceName"
                            :resource-name="relation.resourceName"
                            :resource-fields="relation.resourceFields"
                            :resource-prefix="resourcePrefix"
                    ></table-component>
                </div>
                <div v-if="relation.relationshipType === 'BelongsTo' || relation.relationshipType === 'HasOne'">
                    <single-resource-component
                            :pretty-resource-name="relation.relationshipName"
                            :resource-name="relation.resourceName"
                            :resource-id="relation.resourceId"
                            :resource-fields="relation.resourceFields"
                            :resource-prefix="resourcePrefix"
                    ></single-resource-component>
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
            'resourcePrefix'
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
                axios.get(`/api/${this.resourcePrefix}/${this.resourceName}/${this.resourceId}`)
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