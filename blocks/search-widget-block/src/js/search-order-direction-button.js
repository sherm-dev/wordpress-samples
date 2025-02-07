import { useRef } from '@wordpress/element';
import { useDispatch } from '@wordpress/data';
import searchStore from './search-widget-store.js';
import Button from '@mui/material/Button';
import SwapVert from '@mui/icons-material/SwapVert';

export default function SearchOrderDirectionButton({order}){
	const buttonRef = useRef(null);
	const {setOrder} = useDispatch(searchStore);
	const onOrderChange = () => {
		setOrder(order === "ASC" ? "DESC" : "ASC");
	};
	return(
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
					"&.active": {
						background: '#00728b',
						backgroundColor: '#00728b',
						color: "#fff"
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
			}
			className="search-orderby-button"
			variant="contained" 
			startIcon={
				<SwapVert />
			}  
			onClick={(e) => onOrderChange()}>
			{order === "ASC" ? "Descending" : "Ascending"}
		</Button>
		</>
	);
}