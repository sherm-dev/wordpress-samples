import { useState, useRef } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

import SearchFilter from './search-filter.js';
import { useDispatch } from '@wordpress/data';
import searchStore from './search-widget-store.js';
import findPostTaxonomies from './find-post-taxonomies.js';
import Button from '@mui/material/Button';
import CloseRounded from '@mui/icons-material/CloseRounded';

export default function SearchFilters({postType, filters, opened, onClearFilters, onSearchClick}){
	const filterRef = useRef(null);
	const taxonomies = useSelect((select) => { //not sure this is necessary
		const result = select(coreStore).getTaxonomies({context: "view"});
		
		return findPostTaxonomies(postType, result);
	});

	return (
		<>
		{filters === null && taxonomies !== null && (
			 <>
		 	{taxonomies.map((tax, i) => (
					<>
					<SearchFilter 
						name={tax.name}
						filters={null}
						index={i} 
						opened={opened === i}  
						onSearchClick={onSearchClick} />
					</>
				))}
				</>
		 )}
		
		{filters !== null && (
		 	<>
			{Object.values(filters).map((item, i) => (
				<>
				<SearchFilter 
					name={item.taxonomy.name}
					filters={item}
					index={i} 
					opened={opened === i}  
					onSearchClick={onSearchClick} />
				</>
				))}
			</>
		 )}
		
		<Button 
			sx={
				{
					textTransform: 'none',
					color: "#707070",
					background: 'none',
					backgroundColor: '#fff',
					marginTop: "16px",
					borderRadius:'30px',
					boxShadow: 'none',
					"& .MuiButton-startIcon": {
						background: '#707070',
						backgroundColor: '#707070',
						color: '#fff',
						padding: '3px',
						borderRadius: '50px'
					},
					"& .MuiButton-startIcon .MuiSvgIcon-root": {
						height: '14px',
						width: '14px'
					},
					"&:hover": {
						color:'#00728b',
						background: '#fafafa',
						backgroundColor: '#fafafa'
					}, 
					"&:hover .MuiButton-startIcon": {
						background: '#00728b',
						backgroundColor: '#00728b',
					}
				}
			}
			id="search_widget_filters_clear"
			variant="contained"
			startIcon={<CloseRounded />}
			ref={filterRef} 
			onClick={(e) => {
				onClearFilters();
		}}>Clear Filters</Button>
		</>
	);
}