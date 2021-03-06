<?php declare(strict_types=1);

namespace SolrTest\Controller\Admin;

use SolrTest\Controller\SolrControllerTestCase;

class MappingControllerTest extends SolrControllerTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $schema = $this->solrNode->schema();
        $schema->setSchema([]);
    }

    public function testBrowseAction(): void
    {
        $this->dispatch($this->solrNode->mappingUrl('browse'));
        $this->assertResponseStatusCode(200);
    }

    public function testResourceBrowseAction(): void
    {
        $this->dispatch($this->solrNode->resourceMappingUrl('items', 'browse'));
        $this->assertResponseStatusCode(200);
    }

    public function testAddAction(): void
    {
        $this->dispatch($this->solrNode->resourceMappingUrl('items', 'add'));
        $this->assertResponseStatusCode(200);
    }

    public function testEditAction(): void
    {
        $this->dispatch($this->solrMapping->adminUrl('edit'));
        $this->assertResponseStatusCode(200);
    }

    public function testDeleteConfirmAction(): void
    {
        $this->dispatch($this->solrMapping->adminUrl('delete-confirm'));
        $this->assertResponseStatusCode(200);
    }

    public function testDeleteAction(): void
    {
        $solrMapping = $this->api()->create('solr_mappings', [
            'o:solr_node' => [
                'o:id' => $this->solrNode->id(),
            ],
            'o:resource_name' => 'items',
            'o:field_name' => 'dcterms_description_t',
            'o:source' => 'dcterms:description',
            'o:settings' => [
                'formatter' => '',
            ],
        ])->getContent();

        $forms = $this->getServiceLocator()->get('FormElementManager');
        $form = $forms->get(\Omeka\Form\ConfirmForm::class);
        $this->dispatch($solrMapping->adminUrl('delete'), 'POST', [
            'confirmform_csrf' => $form->get('confirmform_csrf')->getValue(),
        ]);
        $this->assertRedirectTo($this->solrNode->resourceMappingUrl('items'));
    }
}
