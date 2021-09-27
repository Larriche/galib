<?php

namespace Adilarry\GALib;

class Population
{
    /**
     * A collection of individuals
     *
     * @var array
     */
    private $population;

    /**
     * Fitness of population
     *
     * @var double
     */
    private $population_fitness;

    /**
     * Create a new population
     *
     * @param int $population_size Size of population
     * @param mixed $algorithm Client algorithm implementation object
     * @param boolean $random Determines whether we are creating a random population or not
     */
    public function __construct($population_size = null, $algorithm, $random = false)
    {
        $this->population = [];

        if ($algorithm && $population_size) {
            for ($i = 0; $i < $population_size; $i++) {
                $this->population[$i] = new Individual($algorithm, $random);
            }
        }

        $this->population_fitness = -1;
    }

    /**
     * Get the individuals from this population
     *
     * @return array Collection of individuals in this population
     */
    public function get_individuals()
    {
        return $this->population;
    }

    /**
     * Sort the individuals based on fitness and return the individual at the
     * given rank position
     *
     * @param int $index Index to get fittest individual
     * @return Individual The fittest individual
     */
    public function get_fittest($index)
    {
        usort($this->population, function ($individual_a, $individual_b) {
            if ($individual_a->get_fitness() > $individual_b->get_fitness()) {
                return -1;
            } elseif ($individual_b->get_fitness() > $individual_a->get_fitness()) {
                return 1;
            }

            return 0;
        });

        return $this->population[$index];
    }

    /**
     * Set the population fitness for this population
     *
     * @param double $fitness The fitness
     */
    public function set_fitness($fitness)
    {
        $this->population_fitness = $fitness;
    }

    /**
     * Get the fitness of this population
     *
     * @return double The fitness of this population
     */
    public function get_fitness()
    {
        return $this->population_fitness;
    }

    /**
     * Get the size of this population
     *
     * @return int The size of this population
     */
    public function size()
    {
        return count($this->population);
    }

    /**
     * Fix an individual at a given index of the population
     *
     * @param int  $index The index to fix individual
     * @param Individual $individual The individual
     */
    public function set_individual($index, $individual)
    {
        $this->population[$index] = $individual;
    }

    /**
     * Get the individual at the given index
     *
     * @param int $index Desired index
     * @return Individual The individual
     */
    public function get_individual($index)
    {
        return $this->population[$index];
    }

    /**
     * Shuffle the individuals of a population
     */
    public function shuffle()
    {
        shuffle($this->population);
    }

    /**
     * Get the average fitness of individuals in this population
     *
     * @param double The average fitness
     */
    public function get_avg_fitness()
    {
        if ($this->population_fitness == -1) {
            $total_fitness = 0;

            foreach ($this->population as $individual) {
                $total_fitness += $individual->get_fitness();
            }

            $this->population_fitness = $total_fitness;
        }

        return $this->population_fitness / $this->size();
    }

    /**
     * Get a printout of the population
     *
     * @return string Output of the population details
     */
    public function __toString()
    {
        $output = "";

        for ($i = 0; $i < $this->size(); $i++) {
            $output .= "\n\n" . $this->get_fittest($i);
        }

        return $output;
    }
}