import { useState, useEffect} from '@wordpress/element';
import CircularProgress from '@mui/material/CircularProgress';
import SearchWidgetItem from './search-widget-item.js';




export default function SearchWidgetResultList({queryResults, isSearching}){
	
	return (
		<div className="search-widget-result-list">
			<>
			{isSearching && (
				<div className="search-progress" style={{width: '100%', display: 'flex', flexDirection: 'column'}}>
					<CircularProgress sx={{width: '24px', height: '24px', color: '#00728b', alignSelf: 'center'}} />
				</div>
			)}

			{!isSearching && queryResults === undefined || (queryResults !== null && queryResults.posts !== undefined && queryResults.posts.length === 0 ) && (
				<p className="has-medium-font-size" style={{marginTop: '15px'}}>No Results Found</p>
			)}
			{!isSearching && queryResults !== null && queryResults !== undefined && queryResults.posts !== undefined && queryResults.posts.length > 0 && (
				<>
				{ queryResults.posts.map((result, i) => {
					return <SearchWidgetItem result={result} key={i} />;
				})}

				</>
			 )}
			</>
		</div>
	);
}