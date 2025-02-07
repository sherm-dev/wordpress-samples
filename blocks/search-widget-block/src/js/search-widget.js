import { useState, createRoot, useRef, flushSync, useEffect } from '@wordpress/element';
import { useSelect, select, resolveSelect, useDispatch } from '@wordpress/data';
import {store as coreStore} from '@wordpress/core-data';

import searchStore, {postStore, fetchResults} from './search-widget-store.js';





/**** Material UI ***************/
import TextField from '@mui/material/TextField';
import InputAdornment from '@mui/material/InputAdornment';
import IconButton from '@mui/material/IconButton';
import CircularProgress from '@mui/material/CircularProgress';
import Button from '@mui/material/Button';
import ArrowDownwardIcon from '@mui/icons-material/ArrowDownward';

import SearchFilters from './search-filters.js';
import SearchFilter from './search-filter.js';
import SearchWidgetResultList from './search-widget-result-list.js';
import SearchOrder from './search-order.js';

const rootEl = document.getElementById('search_widget_root');
const postType = rootEl !== null ? JSON.parse(rootEl.getAttribute("posttype")) : null;
const defaultOrder = rootEl != null ? rootEl.getAttribute("defaultorder") : null;
const defaultOrderby = rootEl != null ? rootEl.getAttribute("defaultorderby") : null;
const root = rootEl !== null ? createRoot(rootEl) : null;


if(root !== null)
	root.render(<SearchWidget postType={postType} defaultOrder={defaultOrder} defaultOrderby={defaultOrderby} />);
			
				




function SearchWidget({postType, defaultOrder, defaultOrderby}){
	let currentPosts = null;
	const inputRef = useRef(null);
	const searchRef = useRef(null);
	const clearRef = useRef(null);
	const buttonRef = useRef(null);
	const perPage = 20;
	const searchQuery = window.location.search !== undefined && window.location.search.indexOf("?s=") !== -1 ? decodeURIComponent(window.location.search.substring(window.location.search.indexOf("?s=") + 3, (window.location.search.indexOf("&") === -1 ? window.location.search.length : window.location.search.indexOf("&")))).replace("+", " ") : "";
	const [searchTerm, setSearchTerm] = useState(searchQuery);
	const [isError, setIsError] = useState(false);
	const [results, setResults] = useState({});
		
	const { setOpened, setPaging, setIsSearching, clearFilters} = useDispatch(searchStore);
		
	
		
	const opened = useSelect((select) => {
		return select(searchStore).getOpened();
	});
		
	const isSearching = useSelect((select) => {
		return select(searchStore).getIsSearching();
	});
		
	const order = useSelect((select) => {
		return select(searchStore).getOrder() === "" ? defaultOrder : select(searchStore).getOrder();
	});
		
	const orderby = useSelect((select) => {
		return select(searchStore).getOrderby() === "" ? defaultOrderby : select(searchStore).getOrderby();
	});
		
	const paging = useSelect((select) => {
		return select(searchStore).getPaging();
	});
		
	const filters = useSelect((select) => {
		return select(searchStore).getFilters({postType});
	});
	
	
	
	const onSearchClick = () => {
		setIsSearching(true);
		
		if(paging !== 1)
			setPaging(1);
		
		if(opened !== -1)
			setOpened(-1);
	};
		
	const onClearFilters = () => {
		if(searchTerm !== ""){
			clearFilters();
			onSearchClick();
		}else{
			onClearResults();
		}
	};
	
	const onValueChange = (value) => {
		if(value.search(new RegExp(/[<>&/\\$!%"'`~]+/g)) !== -1){ //plus removed
			setIsError(true);
		}else{
			setIsError(false);
		}
		
		setSearchTerm(value);
	};
	
		
	const onClearResults = () => {
		setSearchTerm("");
		setPaging(1);
		clearFilters();
		postStore.dispatch({type: 'SET_POSTS', posts: {max_pages: 0, posts: []}});
	};
	

	function onPostsChange(){
		let previous = currentPosts;
		currentPosts = postStore.getState().postReducer.posts;
		
		if(previous !== currentPosts && previous !== [] && (searchTerm !== "" || isSearching)){
			console.log("SET POSTS ON POSTS CHANGE");
			setResults(currentPosts);
			setIsSearching(false);
		}
		
	}
	
	
	postStore.subscribe(onPostsChange);
	

	useEffect(() => {
		if(!isError)
			postStore.dispatch(fetchResults(
				{
					perPage,
					paging,
					offset: paging * perPage - perPage,
					searchTerm,
					postType,
					order: order,
					orderby: orderby,
					context: "view",
					filters
				}
			));
	}, [paging, order, orderby, isSearching, isError]);
		
	useEffect(() => {
		document.addEventListener("keydown", (e) => {
			if(e.which === 13)
				setIsSearching(true);
		});	
		
		document.body.addEventListener('click', (e) => {
			if(e.target.tagName === "svg" || (e.target !== null && e.target.className !== "" && e.target.className.indexOf("Mui") === -1 
			   && e.target.className.indexOf("PrivateSwitchBase-input") === -1 
			   || e.target.id === "search_widget"
			   || e.target.id === "search_widget_clear"
			   || e.target.id === "search_widget_filters_clear"))
				setOpened(-1);
		});
	}, []);
	return(
		<>
		<div className="search-widget">
			<div className="search-widget-container">
				<div className="error-wrapper" style={{width:'80%'}}>
					<TextField
						sx={
							{
								width: '100%',
								minWidth:'100%',
								color: '#707070',
								outline: "none",
								"&::after": {
									borderBottom: 'none'
								},
								"&:focus": {
									outlineWidth: "0px"
								},
								"& .MuiInputBase-input:focus": {
									outlineWidth: "0px"
								},
								"& .MuiInput-root::before": {
									borderBottomwidth: '0px'
								},
								"& .MuiInputBase-root": {
									borderColor: "#f5f5f5",
									borderRadius: '5px',
									borderWidth: '2px',
									borderStyle: 'solid',
								},
								"& .MuiInputBase-root::after": {
									borderBottom: "none",
								},
								"& .MuiInputBase-input": {
									borderWidth: '0px'
								},
								"& .MuiInput-input": {
									outlineWidth: '0px',
									borderWidth: '0px',
									borderStyle: 'none'
								},
								"& .MuiInputBase-root .MuiButtonBase-root": {
									color: "#707070"
								}
							}
						}
						id="search_widget"
						label=""
						ref={inputRef}
						placeholder="Search"
						value={searchTerm}
						onChange={ (e) => onValueChange(e.target.value)}
						onKeyDown={(e) => {if(e.which === 13 || e.keyCode === 13) onSearchClick()}}
						InputProps={{
						  startAdornment: (
							<InputAdornment position="start">
									<IconButton onClick={(e) => onSearchClick()}>
							  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#707070"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
									</IconButton>
							</InputAdornment>
						  ),
						}}
						variant="standard"
					  />
					{isError && (
					 	<p>Your search contains invalid characters</p>
					 )}
				</div>
				<Button 
					sx={
						{
							textTransform: 'none',
							color: "#00989D",
							paddingTop:'5px',
							paddingBottom: '5px',
							alignSelf: 'center',
							textDecoration: 'underline',
							background: 'none',
							backgroundColor: 'transparent',
							alignSelf: "start",
							marginTop: "0.5rem",
							"&:hover": {
								background: 'none',
								backgroundColor: 'transparent'
							}
						}
					}
					id="search_widget_clear"
					variant="text"
					ref={clearRef} 
					onClick={(e) => {
						onClearResults();
				}}>Clear Results</Button>
			</div>
			<div className="search-filters-container">
						 <SearchFilters 
							postType={postType}
							filters={filters} 
							opened={opened} 
							onClearFilters={onClearFilters}
							onSearchClick={onSearchClick} />
			</div>
			{!isSearching && results !== null && results !== undefined && results.posts !== undefined && results.posts.length > 0 && (
				<div className="search-order-container">
					<SearchOrder order={order} orderby={orderby} />
				</div>
			)}

			<SearchWidgetResultList queryResults={ results } isSearching={isSearching} />
				
			{results !== null && results !== undefined && results.posts !== undefined && results.posts.length > 0 && paging < results.max_pages && (
				<Button 
					ref={ buttonRef }
					endIcon={<ArrowDownwardIcon />}
					sx={
						{
							textTransform: 'none',
							marginRight: 'auto', 
							marginLeft: 'auto', 
							display: 'block',
							background: "#f5f5f5",
							backgroundColor: "#f5f5f5",
							color: '#00728b',
							"& .MuiButton-endIcon": {
								display: 'inline-block'
							},
							"&:hover": {
								background: '#00728b',
								backgroundColor: '#00728b',
								color: "#fff"
							},
							"&:hover .MuiButtonBase-root svg": {
								fill: "#fff"
							}
						}
					} variant="contained" onClick={(e) => 
						{
						   setPaging(paging + 1);
						}
					}>Load More</Button>
			)}
		
		</div>
		</>
	);
}
