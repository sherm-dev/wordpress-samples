import { createSlice, createSelector } from '@reduxjs/toolkit';
import { resolveSelect } from '@wordpress/data';
import {store as coreStore} from '@wordpress/core-data';
import apiFetch from '@wordpress/api-fetch';

import { buildCreateSlice, asyncThunkCreator } from '@reduxjs/toolkit';



const initialState = {
	opened: -1,
	paging: 1,
	isSearching: false,
	order: "ASC",
	orderby: "title",
	filters: null,
	posts: {
		max_pages: 0,
		posts: []
	},
	loading: false
};

const createAppSlice = buildCreateSlice({
  creators: { asyncThunk: asyncThunkCreator },
});

const searchReducer = createAppSlice({
	name: "jewishla-blocks/search-widget-store",
	initialState: () => initialState,
	reducers: (create) => ({
		setPosts: create.reducer(
		  (state, action) => {
			state.posts = {
				...state.posts,
				...action.posts
			};
		  }
		),
		setPaging: create.reducer(
			(state, action) => {
				state.paging = action.page;
			}
		),
		setOpened: create.reducer(
			(state, action)	=>	{
				state.opened = action.opened;
			}
		),
		setIsSearching: create.reducer(
			(state, action)	=>	{
				state.isSearching = action.isSearching;
			}
		),
		setFilters: create.reducer(
			(state, action)	=>	{
				state.filters = {
					...state.filters,
					...action.filters
				};
			}
		),
		clearFilters: create.reducer(
			(state, action)	=>	{
				let filterList = state.filters;
				
				for(var key in filterList){
					if(filterList.hasOwnProperty(key)){
						if(filterList[key].all !== undefined)
							filterList[key].all = false;
						
						if(filterList[key].filters !== undefined){
							for(var index in filterList[key].filters){
								if(filterList[key].filters.hasOwnProperty(index))
									filterList[key].filters[index].checked = false;
							}
						}
					}
				}
				
				state.filters = {
					...state.filters,
					...filterList
				};
			}
		),
		selectFilter: create.reducer(
			(state, action)	=>	{
				let filters = state.filters;
				
				
				
				if(filters[action.filter.taxonomy].filters !== undefined){
					for(var index in filters[action.filter.taxonomy].filters){
						if(filters[action.filter.taxonomy].filters[index].id === action.filter.id)
							filters[action.filter.taxonomy].filters[index].checked = action.checked;
					}
				}
					
				state.filters = {
					...state.filters,
					[action.filter.taxonomy]: {
						...filters[action.filter.taxonomy],
						...state.filters[action.filter.taxonomy]
					}
				};
				
			}
		),
		filterCheckAll: create.reducer(
			(state, action)	=>	{
				let _filters = state.filters;
				
				
				_filters[action.slug].all = action.checked;
				
				if(_filters[action.slug].filters !== undefined){
					for(var index in _filters[action.slug].filters){
						_filters[action.slug].filters[index].checked = action.checked;
					}
				}
				
					
				state.filters = {
					...state.filters,
					[action.slug]: {
						..._filters[action.slug],
						...state.filters[action.slug]
					}
				};
			}
		),
		setOrder: create.reducer(
			(state, action)	=>	{
				state.order = action.order;
			}
		),
		setOrderby: create.reducer(
			(state, action)	=>	{
				state.orderby = action.orderby;
			}
		),
		retrieveFilters: create.asyncThunk(
			async (postType) => {
				const taxonomies = await resolveSelect(coreStore).getTaxonomies({context: "view"});
				const postTax = findPostTaxonomies(postType, taxonomies);
				
				const filters = {};
			
				for(var taxonomy in taxonomies) {
					if(taxonomies.hasOwnProperty(taxonomy)){
						const terms = resolveSelect(coreStore).getEntityRecords('taxonomy', taxonomies[taxonomy].slug, {context: "view", per_page: 100});
						
						if(terms !== undefined && terms !== null && taxonomies[taxonomy].slug !== undefined){
							filters[taxonomies[taxonomy].slug] = {};
							filters[taxonomies[taxonomy].slug].taxonomy = taxonomies[taxonomy];
							filters[taxonomies[taxonomy].slug].filters = formatTerms(terms);
							filters[taxonomies[taxonomy].slug].all = false;
						}
					}
				}
				
				return filters;
			},
			{
				pending: (state) => {
				  state.loading = true
				},
				rejected: (state, action) => {
				  state.loading = false
				},
				fulfilled: (state, action) => {
				  	state.filters = {
						...state.filters,
						...action.filters
					};
				}
		    }
		),
		fetchResults: create.asyncThunk(
			async (args) =>	{
				const posts = await apiFetch( { path: path, method: 'POST', data: {
					page: args.paging,
					per_page: args.perPage,
					offset: args.paging * args.perPage - args.perPage,
					types: [args.postType],
					search: args.searchTerm,
					order: args.order,
					orderby: args.orderby,
					context: args.context,
					term_slugs: args.filters
				} } );
				
				return posts;
			},
			{
				pending: (state) => {
				  state.isSearching = true
				},
				rejected: (state, action) => {
				  state.isSearching = false
				},
				fulfilled: (state, action) => {
				  	state.posts =  {
						max_pages: action.posts.max_pages,
						posts: [
							...action.posts.posts,
							...state.posts.posts
						]
					};
				}
		    }
		),
	}),
	selectors: {
		getPosts: createSelector(
			(state) => state.posts,
			(posts) => posts
		),
		getOpened: createSelector(
			(state) => state.opened,
			(opened) => opened
		),
		getPaging: createSelector(
			(state) => state.paging,
			(paging) => paging
		),
		getIsSearching: createSelector(
			(state) => state.isSearching,
			(isSearching) => isSearching
		),
		getFilters: createSelector(
			(state) => state.filters,
			(filters) => filters
		),
		getOrder: createSelector(
			(state) => state.order,
			(order) => order
		),
		getOrderby: createSelector(
			(state) => state.orderby,
			(orderby) => orderby
		)
	}
});

export default searchReducer;