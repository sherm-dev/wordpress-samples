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

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
export default function save({attributes}) {
	const { postType, defaultOrder, defaultOrderby } = attributes;
	return (
		<div { ...useBlockProps.save({id: "search_widget_root", postType: postType})} postType={JSON.stringify(postType)} defaultOrder={defaultOrder} defaultOrderby={defaultOrderby}>
			<>
			<form role="search" method="get" action="https://dev-block-start-theme-test.pantheonsite.io/" className="wp-block-search__button-outside wp-block-search__icon-button wp-block-search"><label className="wp-block-search__label screen-reader-text" for="wp-block-search__input-4">Search</label><div class="wp-block-search__inside-wrapper "><input class="wp-block-search__input has-avenir-font-family" id="wp-block-search__input-4" placeholder="Enter keywords here..." value="" type="search" name="s" required="" /><button aria-label="Search" className="wp-block-search__button has-avenir-font-family has-icon wp-element-button" type="submit"><svg class="search-icon" viewBox="0 0 24 24" width="24" height="24">
					<path d="M13 5c-3.3 0-6 2.7-6 6 0 1.4.5 2.7 1.3 3.7l-3.8 3.8 1.1 1.1 3.8-3.8c1 .8 2.3 1.3 3.7 1.3 3.3 0 6-2.7 6-6S16.3 5 13 5zm0 10.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5z"></path>
				</svg></button></div></form>
					<div style="height:60px" aria-hidden="true" className="wp-block-spacer"></div>
			</>
		</div>
	);
}
