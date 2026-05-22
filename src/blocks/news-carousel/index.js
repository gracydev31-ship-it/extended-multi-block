import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import './style.scss';
import './editor.scss';

import metadata from './block.json';

registerBlockType('runpartner/news-carousel', {
	...metadata,
	edit() {
		return (
			<div {...useBlockProps()}>
				<p>{__('News Carousel — displays latest 5 posts in an autoplay carousel.', 'extended-multi-block')}</p>
			</div>
		);
	},
	save() {
		return null;
	},
});
