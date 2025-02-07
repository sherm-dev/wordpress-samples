import { useState, useRef, useEffect } from '@wordpress/element';
import { useDispatch } from '@wordpress/data';

import searchStore from './search-widget-store.js';
import filterSelectionCounter from './filter-selection-counter.js';

import List from '@mui/material/List';
import ListItem from '@mui/material/ListItem';
import ListItemIcon from '@mui/material/ListItemIcon';
import ListItemButton from '@mui/material/ListItemButton';
import ListItemText from '@mui/material/ListItemText';
import Checkbox from '@mui/material/Checkbox';
import Divider from '@mui/material/Divider';
import Button from '@mui/material/Button';


import Box from '@mui/material/Box';

function FilterListItem({filter, onCheckboxClick}){
	const onCheck = (checked) => {
		onCheckboxClick(filter, checked);
	};
	
	return(
		<ListItem
			key={filter.slug}
		  >
			<ListItemButton role={undefined} dense>
			  <ListItemIcon>
				<Checkbox
				  edge="start"
				  checked={ filter.checked }
				  tabIndex={-1}
				  value={filter.slug}
				  onChange={(e) => onCheck(e.target.checked)}
				  disableRipple
				  inputProps={{}}
				  sx={{
					'&.Mui-checked': {
					  color: "#00728b",
					},
				  }}
				/>
			  </ListItemIcon>
			  <ListItemText primary={filter.name} />
			</ListItemButton>
		  </ListItem>
	);
}

export default function SearchFilterList({filters, onSearchClick}){
	const applyRef = useRef(null);
	const { addFilter, removeFilter, filterCheckAll, selectFilter } = useDispatch(searchStore);
	
	const onCheckboxClick = (filter, checked) => {
		selectFilter(filter, checked);
	};
	
	const onHeaderChecked = (checked) => {
		filterCheckAll(filters.taxonomy.slug, checked);
	};
	
	return(
		<Box sx={{ zIndex: 1000, border: 1, p: 1, bgcolor: 'background.paper', borderColor: "#a7a8aa", top: "8px"  }}>
			<List sx={{ width: '100%', maxWidth: 360, bgcolor: 'background.paper'}}>
				<ListItem
					key="subheader"
				  >
					<ListItemButton role={undefined} dense>
					  <ListItemIcon>
						<Checkbox
						  edge="start"
						  checked={filters.all}
						  tabIndex={-1}
						  value={-1}
						  onChange={(e) => onHeaderChecked(e.target.checked)}
						  disableRipple
						  inputProps={{}}
						  sx={{
							'&.Mui-checked': {
							  color: "#00728b",
							},
						  }}
						/>
					  </ListItemIcon>
					  <ListItemText primary="Choices" secondary={filterSelectionCounter(filters.filters) + "/" + filters.filters.length +  " Selected"} secondaryTypographyProps={{color: "#a7a8aa"}} />
					</ListItemButton>
				  </ListItem>

				<Divider />
						  
			   <Box sx={
				   {
				   		maxHeight: '300px',
				   		overflowY: "scroll"
			   	   }
			   }>
						  
				{filters.filters != undefined && filters.filters !== [] && (
				 	<>
				 	{filters.filters.map((filter) => {
						return (
							<FilterListItem filter={filter} onCheckboxClick={onCheckboxClick} />
						);
					})}
					</>
				 )}
				 
				 </Box>
				 
				 <ListItem key="apply">
					 <Button 
						sx={
							{
								width: "100%",
								background: '#3AB8B0',
								backgroundColor: '#3AB8B0',
								color: '#fff',
								borderRadius:'30px',
								"&:hover": {
									background: "#00989D",
									backgroundColor: "#00989D"
								} 
							}
						}
						variant="contained" 
						ref={applyRef} 
						onClick={onSearchClick}>
							Apply
					</Button>
				 </ListItem>
			</List>
		  </Box>
	);
}