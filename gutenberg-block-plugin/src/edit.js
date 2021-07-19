import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
const { InspectorControls } = wp.blockEditor;
const {  PanelBody, PanelRow, SelectControl, TextControl} = wp.components;

export default function edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();
	//console.log(attributes)
	return (
		<div {...blockProps}>
				<InspectorControls>
					<PanelBody
						title="Student Block Settings"
						initialOpen={true}
					>
						<PanelRow>
							<TextControl
								label="How many students to display?"
								value={attributes.studentsCount}
								onChange={(newval) => setAttributes({ studentsCount: newval })}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label="Type of students?"
								value={attributes.studentType}
								options={[
									{label: "Active", value: 'true'},
									{label: "Inactive", value: 'false'},
								]}
								onChange={(newval) => setAttributes({ studentType: newval })}
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>

			<ServerSideRender
				block="create-block/ob-test-block"
				attributes={attributes}
			/>
		</div>
		
	);

}
