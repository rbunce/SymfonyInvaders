<?php
/**
 * Copyright (c) Scott Driscoll
 */

namespace SD\Game;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use JMS\DiExtraBundle\Annotation as DI;
use SD\InvadersBundle\Helpers\OutputHelper;
use SD\InvadersBundle\Events;

/**
 * @DI\Service("game.screen_buffer")
 * 
 * @author Richard Bunce <richard.bunce@opensoftdev.com>
 */
class ScreenBuffer
{
    private $screen;
    
    
    public function intialize()
    {
        $this->screen = array();
        for ($i = 0; $i < 30; $i++) {
            for ($j = 0; $j < 100; $j++) {
                $this->screen[$i][$j] = new ScreenBufferUnit();
            }
        }
    }
    
    public function clearScreen($value = ' ')
    {
        foreach ($this->screen as $y => $row) {
            foreach ($row as $x => $unit) {
                $unit->setNext($value);
            }
        }        
    }
  
    public function nextFrame()
    {
        foreach ($this->screen as $y => $row) {
            foreach ($row as $x => $unit) {
                $unit->nextFrame();
            }
        }            
    }
    
    public function putNextValue($x, $y, $value)
    {
        $this->screen[$y][$x]->setNext($value);
    }
    
    public function paintChanges(OutputHelper $output)
    {
        foreach ($this->screen as $y => $row) {
            foreach ($row as $x => $unit) {
                if ($unit->hasChanged()) {
                    //paint $unit->getNext()
                    $output->moveCursorUp(100);
                    $output->moveCursorFullLeft();
                    $output->moveCursorDown($y);
                    if ($x > 0) {
                        $output->moveCursorRight($x);
                    }
                    $output->write($unit->getNext());
            
                }
            }
        }
    }
}
