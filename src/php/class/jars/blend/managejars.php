<?php
namespace jars\blend;

class managejars extends \Blend
{
    public function __construct()
    {
        $this->label = 'Manage';
        $this->linetypes = ['jar'];
        $this->showass = ['list',];

        $this->fields = [
            (object) [
                'name' => 'jar',
                'type' => 'text',
            ],
            (object) [
                'name' => 'description',
                'type' => 'text',
            ],
        ];
    }
}
