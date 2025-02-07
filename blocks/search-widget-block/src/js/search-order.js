import SearchOrderButton from './search-order-button.js';
import SearchOrderDirectionButton from './search-order-direction-button.js';


export default function SearchOrder({order, orderby}){
	return (
		<>
		<SearchOrderButton orderby={orderby} type="title" />
		<SearchOrderButton orderby={orderby} type="date" />
		<SearchOrderButton orderby={orderby} type="relevance" />
		<SearchOrderDirectionButton order={order} />
		</>
	);
}