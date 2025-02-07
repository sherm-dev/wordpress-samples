/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';
import { RadioControl, __experimentalVStack as VStack, CheckboxControl } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { useEffect, useState } from '@wordpress/element';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

const PostTypeCheckboxControl = ({checked, item, onPostTypeChange}) => {
	const [isChecked, setIsChecked] = useState(checked);
	return (
		<CheckboxControl
			__nextHasNoMarginBottom
			label={item.name.substr(0, 1).toLocaleUpperCase() + item.name.substr(1)}
			checked={ isChecked }
			onChange={(check) => {
			    setIsChecked(check);
				onPostTypeChange(item.slug);		  
			} }
		/>
	);
};

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({attributes, setAttributes}) {
	const {postType, defaultOrder, defaultOrderby} = attributes
	const [options, setOptions] = useState({});
	/*const options = useSelect((select) => {
		return select(coreDataStore).getEntitiesConfig('postType');
	});*/
	
	const onPostTypeChange = (value) => {
		let types = postType === undefined ? [] : postType;
		
		if(types.indexOf(value) === -1){
			types.push(value);
		}else{
			types.splice(types.indexOf(value), 1);
		}
		
	
		setAttributes({postType: types});
	};
	
	useEffect(() => {
		apiFetch({path: addQueryArgs("/wp/v2/types", {})}).then((result) => {
			setOptions(result);
		});
	}, []);
	
	return (
		<div { ...useBlockProps({postType: postType})} postType={postType}>
			<VStack>
				{options !== {}  && (
				 	<>
				 		{Object.values(options).map((option) => {
						  	if(option.has_archive || option.slug === "post" || option.slug === "page"){
						 		return (
									<>
						 				<PostTypeCheckboxControl checked={postType !== undefined && postType.indexOf(option.slug) !== -1 } item={option} onPostTypeChange={ onPostTypeChange } />

									</>
								);
							}
							
						})}
				 	</>
				 )}
			</VStack>			
			<RadioControl
				label={ __("Default Order") }
				selected={ defaultOrder }
				options={[
					{ label: "Ascending", value: "ASC" },
					{ label: "Descending", value: "DESC"}
				]}
				onChange={(value) =>  setAttributes({defaultOrder: value})}
			/>
			<RadioControl
				label={ __("Default Order By") }
				selected={ defaultOrderby }
				options={[
					{ label: "Title/Alphabetical", value: "title" },
					{ label: "Date", value: "date"},
					{ label: "Relevance (for search)", value: "relevance"}
				]}
				onChange={(value) =>  setAttributes({defaultOrderby: value})}
			/>
			</div>
		);
	}
