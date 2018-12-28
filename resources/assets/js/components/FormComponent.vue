<template>
    <div>
        <form class="card">
            <div class="card-body">
                <h3 class="card-title">{{ handleText }} {{ resourceName | beautify }}</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div v-for="fieldType, fieldKey in resourceFields" class="form-group">
                            <label class="form-label">{{ fieldKey | beautify }}</label>
                            <input class="form-control" v-model="resourceData[`${fieldKey}`]" v-bind:type="fieldType">
                        </div>
                        <div v-for="relationalMetaData, relationalKey in relationalFields" class="form-group">
                            <label class="form-label">{{ relationalKey | beautify }}</label>
                            <select class="form-control" multiple="true" v-if="relationalData && relationalMetaData.relationshipType === 'HasMany' || relationalMetaData.relationshipType === 'BelongsToMany'" v-model="relationalSelection[`${relationalKey}`]">
                                <option disabled selected>Select {{ relationalKey | beautify }}</option>
                                <option v-if="relationalData" v-for="option in relationalData[`${relationalKey}`]" v-bind:value="option.id" >{{ option[`${relationalMetaData.resourceTitle}`] }}</option>
                            </select>
                            <select class="form-control" v-if="relationalData && relationalMetaData.relationshipType === 'HasOne' || relationalMetaData.relationshipType === 'BelongsTo'" v-model="relationalSelection[`${relationalKey}`]">
                                <option disabled selected>Select {{ relationalKey | beautify }}</option>
                                <option v-if="relationalData" v-for="option in relationalData[`${relationalKey}`]" v-bind:value="option.id" >{{ option[`${relationalMetaData.resourceTitle}`] }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-primary btn-block" @click="handleAction(action)">{{ handleButtonText }}</button>
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
                relationalSelection: {},
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
            if(this.relationalFields)
            {
                this.prepareRelationalSelection();
            }
        },
        mounted() {
        },
        methods: {
            prepareRelationalSelection() {
                let field = null;
                let relationalFields = this.relationalFields;
                for(field in relationalFields)
                {
                    if(relationalFields[field].relationshipType === 'HasMany' || relationalFields[field].relationshipType === 'BelongsToMany')
                    {
                        this.relationalSelection[field] = [];
                    }
                    else if(relationalFields[field].relationshipType === 'HasOne' || relationalFields[field].relationshipType === 'BelongsTo')
                    {
                        this.relationalSelection[field] = null;
                    }
                }
            },
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
                this.resourceData.relations = this.relationalSelection;
                this.resourceData.relationalFields = this.relationalFields;
                console.log(this.resourceData);

                // axios.post(`/api/otter/${this.resourceName}`, this.resourceData)
                //     .then(response => {
                //         console.log("success");
                //         // window.location = response.data.redirect;
                //         window.location = `/otter/${this.resourceName}`;
                //     })
                //     .catch(e => {
                //         this.error = 'Could not save. Please check your values or try again later.';
                //     })
                //     .finally(() => {
                //         this.loading = false;
                //     });
            },
            handleUpdate(e) {
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