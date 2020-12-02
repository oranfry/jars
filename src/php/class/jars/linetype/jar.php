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
            '{t}.jar' => (object) [
                'expression' => ':{t}_jar',
                'type' => 'varchar(40)',
            ],
            '{t}.description' => (object) [
                'expression' => ':{t}_description',
                'type' => 'varchar(255)',
            ],
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
