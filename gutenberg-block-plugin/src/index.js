import { registerBlockType } from '@wordpress/blocks';

import save from './save';
import edit from './edit';

 
registerBlockType( 'create-block/ob-test-block', {
    title: 'Student Widget',
    category: 'widgets',
    icon: 'welcome-learn-more',
    apiVersion: 2,
 
	attributes: {
		studentsCount: {
			type: 'string',
			default: '5'
		},
		studentType: {
			type: 'string',
			default: 'true'
		},

	},
    edit: edit,
	save: save
} );
