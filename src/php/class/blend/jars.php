<?php
namespace blend;

class jars extends \Blend
{
    public $label = 'Jars';

    public function __construct()
    {
        $jars = array_values(array_unique(array_merge(
            get_values('transaction', 'jar'),
            get_values('transfer', 'fromjar'),
            get_values('transfer', 'tojar')
        )));

        sort($jars);

        $this->linetypes = ['jartransaction', 'transferin', 'transferout'];
        $this->hide_types = ['transferout' => 'transferin'];
        $this->groupby = 'date';
        $this->fields = [
            (object) [
                'name' => 'icon',
                'type' => 'icon',
                'derived' => true,
            ],
            (object) [
                'name' => 'date',
                'type' => 'date',
                'groupable' => true,
                'main' => true,
            ],
            (object) [
                'name' => 'jar',
                'type' => 'text',
                'filteroptions' => $jars,
                'groupable' => true,
                'main' => true,
            ],
            (object) [
                'name' => 'account',
                'type' => 'text',
                'default' => 'jartransfer',
            ],
            (object) [
                'name' => 'description',
                'type' => 'text',
                'default' => '',
                'sacrifice' => true,
            ],
            (object) [
                'name' => 'amount',
                'type' => 'number',
                'summary' => 'sum',
                'dp' => 2,
            ],
            (object) [
                'name' => 'broken',
                'type' => 'class',
                'default' => '',
            ],
        ];
        $this->past = true;
        $this->cum = true;
        $this->showass = ['list', 'calendar', 'graph', 'summaries'];
    }
}
