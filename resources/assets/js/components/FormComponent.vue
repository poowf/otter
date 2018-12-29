<template>
    <div>
        <form>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{ handleText }} {{ resourceName | beautify }}</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div v-for="fieldType, fieldKey in resourceFields" class="form-group">
                                <label class="form, mn7654b3-label">{{ fieldKey | beautify }}</label>
                                <input class="form-control" v-model="resourceData[`${fieldKey}`]" v-bind:type="fieldType">
                            </div>
                            <div v-for="relationalMetaData, relationalKey in relationalFields" v-if="relationalData && relationalMetaData.relationshipType === 'BelongsTo' || relationalMetaData.relationshipType === 'BelongsToMany'" class="form-group">
                                <label class="form-label">{{ relationalKey | beautify }}</label>
                                <select class="form-control" multiple="true" v-if="relationalData && relationalMetaData.relationshipType === 'BelongsToMany'" v-model="relationalMetaData.relationshipId">
                                    <option disabled selected>Select {{ relationalKey | beautify }}</option>
                                    <option v-if="relationalData" v-for="option in relationalData[`${relationalKey}`]" v-bind:value="option.id">{{ option[`${relationalMetaData.resourceTitle}`] }}</option>
                                </select>
                                <select class="form-control" v-if="relationalData && relationalMetaData.relationshipType === 'BelongsTo'" v-model="relationalMetaData.relationshipId">
                                    <option disabled selected>Select {{ relationalKey | beautify }}</option>
                                    <option v-if="relationalData" v-for="option in relationalData[`${relationalKey}`]" v-bind:value="option.id">{{ option[`${relationalMetaData.resourceTitle}`] }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary btn-block" @click="handleAction(action)">{{ handleButtonText }}</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "FormComponent",
        props: [
            'resourceId',
            'resourceName',
            'resourceFields',
            'relationalFields',
            'action',
        ],
        data() {
            return {
                handleText: 'Create',
                handleButtonText: 'Create',
                loading: false,
                resourceData: {},
                relationalData: {},
            }
        },
        created() {
            if(this.action === 'edit')
            {
                this.handleText = "Edit";
                this.handleButtonText = "Update";
                this.fetchResource();
            }
            else if(this.action === 'create')
            {
                this.handleText = "Create";
                this.handleButtonText = "Create";
            }
            this.fetchRelationalItems();
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
                    });
            },
            fetchRelationalItems() {
                axios.get(`/api/otter/${this.resourceName}/relational`)
                    .then(response=>{
                        this.relationalData = response.data.data;
                    })
                    .catch(e => {
                        this.error = `Could not retrieve ${this.resourceName}. Server error.`;
                    })
                    .finally(() => {
                    });
            },
            handleAction(action) {
                if(action === 'edit')
                {
                    this.handleUpdate();
                }
                else if(action === 'create')
                {
                    this.handleStore();
                }
            },
            handleStore(e) {
                this.resourceData.relationalFields = this.relationalFields;

                axios.post(`/api/otter/${this.resourceName}`, this.resourceData)
                    .then(response => {
                        console.log("success");
                        // window.location = response.data.redirect;
                        window.location = `/otter/${this.resourceName}`;
                    })
                    .catch(e => {
                        this.error = 'Could not save. Please check your values or try again later.';
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            handleUpdate(e) {
                this.resourceData.relationalFields = this.relationalFields;

                axios.patch(`/api/otter/${this.resourceName}/${this.resourceId}`, this.resourceData)
                    .then(response => {
                        console.log("success");
                        this.fetchResource();
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