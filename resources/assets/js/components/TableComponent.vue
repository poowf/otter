<template>
    <div>
        <div class="row row-cards row-deck">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ resourceName | beautify }}</h3>
                        <div class="action-container">
                            <div class="input-icon d-inline-block pr-3">
                                <input type="text" class="form-control resource-search" placeholder="Search..." v-model.lazy="query" v-debounce="300">
                                <span class="input-icon-addon pr-3"><i class="fe fe-search"></i></span>
                            </div>
                            <a data-toggle="tooltip" data-original-title="Create" class="icon d-inline-block" v-bind:href="`/otter/${resourceName}/create`"><i class="fe fe-plus"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap">
                            <thead>
                                <tr>
                                    <th v-for="tableType, tableKey in resourceFields" @click="sort(tableKey)"
                                        v-if="tableType != 'textarea'"
                                        :class=" ['sortable', { 'sorted-by' : currentSortKey === tableKey },  (currentSortKey === tableKey ? currentSortDirection : '')]"
                                    >{{ tableKey | beautify }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="resource, index in filterResults">
                                    <td v-for="fieldType, fieldKey in resourceFields"
                                        v-if="fieldType != 'textarea'"
                                        v-html="highlight(resource[fieldKey])"
                                    >{{ resource[`${fieldKey}`] }}</td>
                                    <td class="text-right">
                                        <a class="btn btn-secondary btn-sm btn-action" v-bind:href="`/otter/${resourceName}/${resource.route_key}/`">View</a>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-secondary btn-sm btn-dropdown-action dropdown-toggle" data-toggle="dropdown">Action</button>
                                            <div class="dropdown-menu dropdown-menu-dark">
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
        <div class="row justify-content-between mb-3">
            <div class="col-2">
                <button class="btn btn-pill btn-secondary btn-resource-navigation" @click="prevPage()" :disabled="resourceLinksData.prev == null">Prev Page</button>
            </div>
            <div class="col-2">
                <button class="btn btn-pill btn-secondary btn-resource-navigation float-right" @click="nextPage()" :disabled="resourceLinksData.next == null">Next Page</button>
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
            'resourceName',
            'resourceFields',
            'relationship',
            'relation',
            'parentResourceId',
            'parentResourceName'
        ],
        data() {
            return {
                query: '',
                loading: false,
                currentPageIndex: 1,
                resourceData: [],
                resourceMetaData: [],
                resourceLinksData: [],
                currentSelectedResource: null,
                currentSortKey:'id',
                currentSortDirection:'asc',
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
            prevPage() {
                this.currentPageIndex = --this.currentPageIndex;
                this.fetchResourceIndex();
            },
            nextPage() {
                this.currentPageIndex = ++this.currentPageIndex;
                this.fetchResourceIndex();
            },
            sort:function(sortParameter) {
                //if sortParameter == current sort, reverse
                if(sortParameter === this.currentSortKey) {
                    this.currentSortDirection = this.currentSortDirection === 'asc' ? 'desc' : 'asc';
                }
                this.currentSortKey = sortParameter;
            },
            fetchResourceIndex(resourceUrl = this.resourceEndpoint) {
                axios.get(resourceUrl)
                    .then(response=> {
                        this.resourceData = response.data.data;
                        this.resourceMetaData = response.data.meta;
                        this.resourceLinksData = response.data.links;
                        this.currentPageIndex = this.resourceMetaData.current_page;
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
            highlight(text) {
                if(!this.query) return text;
                return String(text).replace(new RegExp(this.query, 'gi'), '<span class="highlight">$&</span>');
            },
            getSearchFields(option) {
                let mappedFieldKeys = Object.keys(this.resourceFields).map(fieldKey => {
                    return option[fieldKey];
                })
                mappedFieldKeys.push(option.id);

                return mappedFieldKeys;
            },
        },
        computed: {
            currentPage() {
                return this.currentPageIndex;
            },
            resourceEndpoint() {
                if(this.relationship)
                {
                    return `/api/otter/${this.parentResourceName}?page=${this.currentPage}&resourceId=${this.parentResourceId}&relationshipName=${this.relation.relationshipName}&relationshipResourceName=${this.relation.resourceName}`;
                }
                else
                {
                    return `/api/otter/${this.resourceName}?page=${this.currentPage}`;
                }
            },
            sortedResources() {
                return this.resourceData.sort((nextResource, currentResource) => {
                    let modifier = 1;
                    if(this.currentSortDirection === 'desc') modifier = -1;
                    if(nextResource[this.currentSortKey] < currentResource[this.currentSortKey]) return -1 * modifier;
                    if(nextResource[this.currentSortKey] > currentResource[this.currentSortKey]) return 1 * modifier;
                    return 0;
                });
            },
            filterResults() {
                if(!this.query) return this.sortedResources;

                const preparedQuery = fz.prepareQuery(this.query);
                const scores = {};

                return this.sortedResources
                    .map((option, index) => {
                        const scorableFields = this.getSearchFields(option);
                        let scoredFields = scorableFields.map(toScore => fz.score(toScore, this.query, { preparedQuery }));

                        scores[option.id] = Math.max(...scoredFields);

                        return option;
                    })
                    .filter(option => scores[option.id] > 1)
                    .sort((a, b) => scores[b.id] - scores[a.id]);
            }
        }
    }
</script>