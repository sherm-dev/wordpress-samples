import { thunk as thunkMiddleware} from 'redux-thunk';
import { createReduxStore, register, resolveSelect, dispatch } from '@wordpress/data';
import {store as coreStore} from '@wordpress/core-data';
import apiFetch from '@wordpress/api-fetch';
import { combineReducers, createStore, applyMiddleware } from 'redux';
import createMiddleware from '@wordpress/redux-routine';
import findPostTaxonomies from './find-post-taxonomies.js';


//import apiFetch from '@wordpress/api-fetch';

const DEFAULT_STATE = {
	opened: -1,
	paging: 1,
	isSearching: false,
	order: "",
	orderby: "",
	filters: null,
	posts: null 
};

const formatTerms = (terms) => {
	let termList = [];
	
	if(terms !== undefined && terms !== null){
		for(const value of terms){
			value.checked = false;
		}
	}
	
	return terms;
};




const middlewareFetch = createMiddleware({
	async FETCH_RESULTS(action){
		const path = '/wp-json/jewishla/v1/complex-search'; //+ argsToString();
		
			
		return apiFetch( { path: path, method: 'POST', data: {
				page: action.args.paging,
				per_page:  action.args.perPage,
				offset:  action.args.paging *  action.args.perPage -  action.args.perPage,
				types: action.args.postType,
				search:  action.args.searchTerm,
				order:  action.args.order,
				orderby:  action.args.orderby,
				context:  action.args.context,
				term_slugs:  action.args.filters
			} } );
	}
});

export function* fetchResults(args){
	const result = yield {type: 'FETCH_RESULTS', args};
	return {type: 'SET_POSTS', posts: result};
}

const postReducer = (state = DEFAULT_STATE, action) => {
	switch(action.type) {
		case 'SET_POSTS':
			return {
				...state,
				posts: {
					max_pages: action.posts.max_pages,
					posts: action.posts.posts
				},
			};
	}
	
	return state;
};

export const postStore = createStore(combineReducers({postReducer}), applyMiddleware(middlewareFetch));

const searchStore = createReduxStore( "jewishla-blocks/search-widget-store", {
	reducer( state = DEFAULT_STATE, action ) {
		switch ( action.type ) {
			case 'SET_PAGING':
				return {
					...state,
					paging: action.page
				};
			case 'SET_OPENED':
				return {
					...state,
					opened: action.opened
				};
			case 'IS_SEARCHING':
				return {
					...state,
					isSearching: action.isSearching
				};
			case 'SET_FILTERS':
				return {
					...state,
					filters: {
						...state.filters,
						...action.filters
					}
				};
			case 'CLEAR_FILTERS':
				let filterList = state.filters;
				
				if(filterList !== undefined){
					for(const [key, value] of Object.entries(filterList)){
						value.all = false;

						if(value.filters !== undefined){
							for(const filter of value.filters){
								filter.checked = false;
							}
						}
					}
				}
				
				return {
					...state,
					filters: {
						...state.filters,
						...filterList,	
					}
				};
			case 'SELECT_FILTER':
				let filters = state.filters;
				
				if(filters[action.filter.taxonomy].filters !== undefined){
					for(var index in filters[action.filter.taxonomy].filters){
						if(filters[action.filter.taxonomy].filters[index].id === action.filter.id)
							filters[action.filter.taxonomy].filters[index].checked = action.checked;
					}
				}
				
				return {
					...state,
					filters: {
						...state.filters,
						[action.filter.taxonomy]: {
							...filters[action.filter.taxonomy],
							...state.filters[action.filter.taxonomy]
						}
					}
				};
			case "FILTER_CHECK_ALL":
				let _filters = state.filters;
				
				_filters[action.slug].all = action.checked;
				
				if(_filters[action.slug].filters !== undefined){
					for(var index in _filters[action.slug].filters){
						_filters[action.slug].filters[index].checked = action.checked;
					}
				}
				return {
					...state,
					filters: {
						...state.filters,
						[action.slug]: {
							..._filters[action.slug],
							...state.filters[action.slug]
						}
					}
				};
			case 'SET_ORDER':
				return {
					...state,
					order: action.order
				};
			case 'SET_ORDERBY':
				return {
					...state,
					orderby: action.orderby
				};
		}
		
		

		return state;
	},
	//TODO: array reverse on order set, instead of refetching
	actions: {
		setPaging(page) {
			return {
				type: 'SET_PAGING',
				page
			};
		},
		setOpened(opened) {
			return {
				type: 'SET_OPENED',
				opened
			};
		},
		setIsSearching(isSearching) {
			return {
				type: 'IS_SEARCHING',
				isSearching
			};
		},
		setFilters(filters) {
			return {
				type: 'SET_FILTERS',
				filters
			};
		},
		clearFilters() {
			return {
				type: 'CLEAR_FILTERS'
			};
		},
		selectFilter(filter, checked) {
			return {
				type: "SELECT_FILTER",
				filter,
				checked
			};
		},
		filterCheckAll(slug, checked) {
			return {
				type: "FILTER_CHECK_ALL",
				slug,
				checked
			};
		},
		retrieveTerms( taxonomy ) {
			return {
				type: "RETRIEVE_TERMS",
				taxonomy
			};
		},
		retrieveTaxonomies(postType) {
			return {
				type: "RETRIEVE_TAXONOMY",
				postType
			};
		},
		setOrder(order) {
			return {
				type: 'SET_ORDER',
				order
			};
		},
		setOrderby(orderby) {
			return {
				type: 'SET_ORDERBY',
				orderby
			};
		}
	},
	selectors: {
		getOpened(state = DEFAULT_STATE, args) {
			const { opened } = state;
			return opened;
		},
		getPaging(state = DEFAULT_STATE, args) {
			const { paging } = state;
			return paging;
		},
		getIsSearching(state = DEFAULT_STATE, args) {
			const { isSearching } = state;
			return isSearching;
		},
		getFilters(state = DEFAULT_STATE, args) {
			const { filters } = state;
			return filters;
		},
		getOrder(state = DEFAULT_STATE, args) {
			const { order } = state;
			return order;
		},
		getOrderby(state = DEFAULT_STATE, args) {
			const { orderby } = state;
			return orderby;
		}
	},
	controls: {
		RETRIEVE_TERMS( action ) {
			return resolveSelect(coreStore).getEntityRecords('taxonomy', action.taxonomy.slug, {context: "view", per_page: 100});
		},
		RETRIEVE_TAXONOMY( action ) {
			return resolveSelect(coreStore).getTaxonomies({context: "view"});
		}
	},
	resolvers: {
		*getFilters(args) {
			const taxonomies = yield { type: 'RETRIEVE_TAXONOMY', postType: args.postType };
			const filters = {};
			
			for(const taxonomy of findPostTaxonomies(args.postType, taxonomies)){
				const terms = yield { type: 'RETRIEVE_TERMS', taxonomy: taxonomy };
				
				if(terms !== undefined && terms !== null && taxonomy.slug !== undefined){
					filters[taxonomy.slug] = {};
					filters[taxonomy.slug].taxonomy = taxonomy;
					filters[taxonomy.slug].filters = formatTerms(terms);
					filters[taxonomy.slug].all = false;
				}
			}	
			
			return {type: "SET_FILTERS", filters};
		}
	}
} );

register( searchStore );



export default searchStore;