Array.prototype.containsAll = function(subArray){
	let sorted = this.sort();
	let subSorted = subArray.sort();
	let long = sorted.length >= subSorted.length ? sorted : subSorted;
	let short = long === sorted ? subSorted : sorted; 
	let longCounter = long.indexOf(short[0]);
	let match = 0;
	let shortCounter = 0;
	
	while(longCounter !== -1 && longCounter < long.length){
		if(long[longCounter] === short[shortCounter]){
			match++;
			shortCounter++;
			longCounter++;
		}else{
			longCounter++;
		}
	}
	
	return match === short.length;
};

Array.prototype.containsAny = function(subArray){
	let sorted = this.sort();
	let subSorted = subArray.sort();
	let long = sorted.length >= subSorted.length ? sorted : subSorted;
	let short = long === sorted ? subSorted : sorted; 
	let longCounter = long.indexOf(short[0]);
	let match = 0;
	let shortCounter = 0;
	
	while(longCounter !== -1 && longCounter < long.length){
		if(long[longCounter] === short[shortCounter]){
			match++;
			shortCounter++;
			longCounter++;
		}else{
			longCounter++;
		}
	}
	
	return match > 0;
};

export default function findPostTaxonomies(postType, result){
	var taxonomies = [];

	if(result !== undefined && result !== null){
		for(const value of result){
			if(postType.containsAny(value.types))
				taxonomies.push(value);
		}
	}

	return taxonomies;
}