<template>
    <div>
        <form class="card">
            <div class="card-body">
                <h3 class="card-title">{{ handleText }} {{ prettyResourceName }}</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div v-for="fieldType, fieldKey in resourceFields" class="form-group">
                            <label class="form-label">{{ fieldKey }}</label>
                            <input class="form-control" v-model="resourceData[`${fieldKey}`]" v-bind:type="fieldType">
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
            'prettyResourceName',
            'resourceId',
            'resourceName',
            'resourceFields',
            'action'
        ],
        data() {
            return {
                handleText: 'Create',
                handleButtonText: 'Create',
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
                axios.post(`/api/otter/${this.resourceName}`, this.resourceData)
                    .then(response => {
                        console.log(response);
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
                axios.patch(`/api/otter/${this.resourceName}/${this.resourceId}`, this.resourceData)
                    .then(response => {
                        console.log(response);
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