export default function filterSelectionCounter(filters){
	let count = 0;

	if(filters !== undefined && filters !== null){
		for(var key in filters){
			if(filters.hasOwnProperty(key)){
				if(filters[key].checked)
					count++;
			}
		}
	}

	return count;
}