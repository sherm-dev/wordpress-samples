import { __experimentalHeading as Heading, __experimentalText as Text, ExternalLink } from '@wordpress/components';
import { useState } from '@wordpress/element';
//import TagIcon from '@mui/icons-material/Tag';
import Chip from '@mui/material/Chip';
import LocalOfferIcon from '@mui/icons-material/LocalOffer';

//import SearchImage from '../../../common/search-widget-image.js';

export default function SearchWidgetItem({result}){
	return (
		<>
		<div className="search-result-item" style={{marginBottom: "60px"}}>
			<div className="wp-block-columns is-not-stacked-on-mobile is-layout-flex wp-block-columns-is-layout-flex">
				<div className="wp-block-column is-layout-flow wp-block-column-is-layout-flow" style={{width: '100px',flexBasis: '100px'}}>
					{result.featured_image && (
						<figure style={{aspectRatio:1,width:"60px",height:'60px'}} className="wp-block-image"><img src={result.featured_image} alt={result.post_title} style={{borderRadius:"50px",width:"100%",height:"100%",objectFit:"cover"}} loading="lazy" /></figure>
					)}
				</div>
				<div className="wp-block-column" style={{width:'90%'}}>
				<Heading level="3"><a href={result.permalink} title={result.post_title} style={{color:"#01395d"}} dangerouslySetInnerHTML={{__html: result.post_title }}></a></Heading>
				<p className="link"><a href={result.permalink} title={result.post_title}>{result.permalink}</a></p>
				<p className="excerpt">{result.post_excerpt}</p>
				<>
				{result.meta !== undefined && (
					<div className="program-block">
						<>
						{result.meta.contact !== undefined && result.meta.contact !== "" && (
				 			<>
						 	<span className="program-contact">{result.meta.contact}</span><div className="clear"></div>
							</>
						 )}
						{result.meta.email !== undefined && result.meta.email !== "" && (
							<>
						 	<span className="program-contact">{result.meta.email}</span><div className="clear"></div>
							</>
						 )}
						{result.meta.phone !== undefined && result.meta.phone !== "" && (
							<>
						 	<span className="program-contact">{result.meta.phone}</span><div className="clear"></div>
							</>
						 )}
						{result.meta.address !== undefined && result.meta.address !== "" && (
						 	<span className="program-contact">{result.meta.address} </span>
						 )}
						{result.meta.address2 !== undefined && result.meta.address2 !== "" && (
							<>
						 	<span className="program-contact">{result.meta.address2}</span><span className="program-separator">,</span>
							</>
						 )}
						<div className="clear"></div>
						{result.meta.city !== undefined && result.meta.city !== "" && (
							<>
						 	<span className="program-contact">{result.meta.city}</span><span className="program-separator">,</span>
							</>
						 )}
						{result.meta.state !== undefined && result.meta.state !== "" && (
						 	<span className="program-contact">{result.meta.state} </span>
						 )}
						{result.meta.zip !== undefined && result.meta.zip !== "" && (
						 	<span className="program-contact">{result.meta.zip} </span>
						 )}
						</>
					</div>
				)}
				</>
				<>
				{result.terms !== null && result.terms !== [] && (
					<>	
					<ul className="tags">
						{result.terms.map((term) => {
							return <li><Chip icon={<LocalOfferIcon sx={{color:"#707070"}} />} sx={{color: "#707070", borderWidth: '0px', background: '#fff', backgroundColor: '#fff', marginRight: '8px'}} label={term.name} /></li>;
						})}
					</ul>
					</>
				)}
				</>
				</div>
			</div>
		</div>
		</>
	);
}