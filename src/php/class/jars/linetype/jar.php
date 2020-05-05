<?php
namespace jars\linetype;

class jar extends \Linetype
{
    function __construct()
    {
        $this->label = 'Jar';
        $this->table = 'jar';

        $this->fields = [
            (object) [
                'name' => 'jar',
                'type' => 'text',
                'fuse' => '{t}.jar',
            ],
            (object) [
                'name' => 'description',
                'type' => 'text',
                'fuse' => '{t}.description',
            ],
        ];

        $this->unfuse_fields = [
            '{t}.jar' => ':{t}_jar',
            '{t}.description' => ':{t}_description',
        ];
    }

    function validate($line) {
        $errors = [];

        if (!@$line->jar) {
            $errors[] = 'no jar name';
        }

        return $errors;
    }
}
