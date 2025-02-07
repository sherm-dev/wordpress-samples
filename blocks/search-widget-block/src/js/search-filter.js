import { useState, useRef, useEffect, flushSync } from '@wordpress/element';
import {useSelect, useDispatch } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import searchStore from './search-widget-store.js';
import SearchFilterList from './search-filter-list.js';
import filterSelectionCounter from './filter-selection-counter.js';
import Button from '@mui/material/Button';
import FilterAltSharp from '@mui/icons-material/FilterAltSharp';
import Popper from '@mui/material/Popper';
import CircularProgress from '@mui/material/CircularProgress';



function FilterCount({filters}){
	return <>({filterSelectionCounter(filters)})</>;
}

export default function SearchFilter({name, filters, index, opened, onSearchClick}){
	const filterRef = useRef(null);
	const { setOpened } = useDispatch(searchStore);
	
	const onFilterClick = (e) => {
		if(opened){
			setOpened(-1);
		}else{
			setOpened(index);
		}
	};
	
	return (
		<>
		<Button 
			sx={
				{
					textTransform: 'none',
					background: '#fafafa',
					backgroundColor: '#fafafa',
					color: '#00728b',
					marginRight:'8px',
					marginTop: "16px",
					borderRadius:'30px',
					boxShadow: 'none',
					"&:hover": {
						background: '#00728b',
						backgroundColor: '#00728b',
						color: "#fff"
					},
					"&:hover .MuiButtonBase-root svg": {
						fill: "#fff"
					}
				}
			}
			variant="contained" 
			startIcon={<FilterAltSharp />} 
			ref={filterRef} 
			onClick={onFilterClick}>
			<>{name} </>
		{filters === null && (
		 	<CircularProgress sx={{maxHeight: '14px', maxWidth: '14px', width: '14px', height: '14px', color: '#00728b'}} />
		)}
		{filters !== null && filters.filters !== undefined  && (
			 <FilterCount filters={filters.filters} />
		)}
		</Button>
			
		{filters !== null && (
			<Popper open={opened} anchorEl={filterRef.current}>
				<SearchFilterList filters={filters} onSearchClick={onSearchClick} />
			</Popper>
		)}
		</>
	);
}