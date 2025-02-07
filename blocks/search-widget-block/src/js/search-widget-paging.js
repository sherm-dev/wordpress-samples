import { useRef, useEffect } from '@wordpress/element';

import Button from '@mui/material/Button';
import ArrowDownwardIcon from '@mui/icons-material/ArrowDownward';

export default function SearchWidgetPaging({paging, onPagingChange, totalPages}){
	const buttonRef = useRef(null);
	
	return (
		<>
		{paging < totalPages && (
			<Button 
		 		ref={ buttonRef }
		 		endIcon={<ArrowDownwardIcon />}
		 		sx={
		 			{
						textTransform: 'none',
		 				marginRight: 'auto', 
		 				marginLeft: 'auto', 
		 				display: 'block',
		 				background: '#fafafa',
						backgroundColor: '#fafafa',
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
						onPagingChange();
					}
				}>Load More</Button>
		)}
		</>
	);
}