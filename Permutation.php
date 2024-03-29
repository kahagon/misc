<?php

class Permutation implements Iterator {

    private $elements = array();

    private $currentElementIndex = 0;

    private $depth = 0;

    public function __construct(array $list) {
        $this->depth = count($list);
        for ($i = 0; $i < $this->depth; $i++) {
            $l = $list[$i];
            $this->elements[] = (object)array(
                'head' => $l,
                'tail' => new Permutation(self::except($list, $i))); 
        } 
    }

    public function current() {
        if (count($this->elements) < 1) return array();

        $currentHead = $this->elements[$this->currentElementIndex]->head;
        $currentTail = $this->elements[$this->currentElementIndex]->tail->current();
        return array_merge(array($currentHead), !$currentTail ? array() : $currentTail);
    } 

    private static function except(array $list, $exceptIndex) {
        $shifted = array();
        $length = count($list);
        for ($i = 0; $i < $length; $i++) {
            if ($i == $exceptIndex) continue;
            $shifted[] = $list[$i];
        }
        return $shifted;
    }

    public function next() {
        $this->_next();
    }

    private function _next() {
        if ($this->currentElementIndex >= count($this->elements)) {
            return false;
        }
        $currentTail = $this->elements[$this->currentElementIndex]->tail;
        $result = false;
        if (!$currentTail->_next()) {
            if ($this->currentElementIndex >= count($this->elements) - 1) {
                $result = false;
            } else {
                $result = true;
            }
            $this->currentElementIndex++;
        } else {
            $result = true;
        }

        return $result;
    }

    public function key() {
        return $this->currentElementIndex;
    }

    public function rewind() {
        $this->currentElementIndex = 0;
        foreach ($this->elements as $elem) {
            $elem->tail->rewind();
        }
    }

    public function valid() {
        return $this->currentElementIndex < count($this->elements);
    }
}
/*
$permutation = new Permutation(str_split('hello'));
foreach ($permutation as $p) {
        print implode('', $p) . PHP_EOL;
}
//*/
