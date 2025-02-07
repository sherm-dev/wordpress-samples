import classNames from 'classnames';
import { useRef } from '@wordpress/element';
import { useDispatch } from '@wordpress/data';
import searchStore from './search-widget-store.js';
import Button from '@mui/material/Button';
import SortSharp from '@mui/icons-material/SortSharp';



export default function SearchOrderButton({orderby, type}){
	const buttonRef = useRef(null);
	const {setOrderby} = useDispatch(searchStore)
	const btnClass = classNames(
		{
			'active': orderby === type
		}
	);
	
	const onOrderbyChange = () => {
		setOrderby(type);
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
			className={btnClass}
			variant="contained" 
			startIcon={
				<SortSharp />
			} 
			onClick={(e) => onOrderbyChange()}>
			{type.substring(0, 1).toUpperCase() + type.substring(1)}
		</Button>
		</>
	);
}