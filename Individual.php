<?php

namespace Adilarry\GALib;

class Individual
{
    /**
     * This is the genetic makeup of an individual
     *
     * @var array
     */
    private $chromosome;

    /**
     * Fitness of the individual
     *
     * @var double
     */
    private $fitness;


    /**
     * Create a new individual
     *
     * @param mixed $algorithm Client algorithm controller class
     * @param boolean Determine whether to create a random individual or not
     */
    public function __construct($algorithm, $random = false)
    {
        if ($algorithm) {
            if (!$random) {
                $this->chromosome = $algorithm->create_individual();
            } else {
                $this->chromosome = $algorithm->create_random_individual();
            }
        } else {
            $this->chromosome = [];
        }
    }

    /**
     * Get this individual's chromosome
     *
     * @return array The chromosome
     */
    public function get_chromosome()
    {
        return $this->chromosome;
    }

    /**
     * Get the length of the individual's chromosome
     *
     * @return int The length
     */
    public function get_chromosome_length()
    {
        return count($this->chromosome);
    }

    /**
     * Fix a gene at the given location of the chromosome
     *
     * @param int $index The location to insert the gene
     * @param int $gene The gene
     */
    public function set_gene($index, $gene)
    {
        $this->chromosome[$index] = $gene;
    }

    /**
     * Get the gene at the specified location
     *
     * @param $index The location to get the gene at
     * @return int The value representing the gene at that location
     */
    public function get_gene($index)
    {
        return $this->chromosome[$index];
    }

    /**
     * Set the fitness param for this individual
     *
     * @param double $fitness The fitness of this individual
     */
    public function set_fitness($fitness)
    {
        $this->fitness = $fitness;
    }

    /**
     * Get the fitness for this individual
     *
     * @return double The fitness of the individual
     */
    public function get_fitness()
    {
        return $this->fitness;
    }

    /**
     * Get a printout of the individual
     *
     * @return string Output of the individual details
     */
    public function __toString()
    {
        return $this->get_chromosome_string() . '(' . $this->fitness . ')';
    }

    /**
     * Get the chromosome of the individual as a string
     *
     * @return string Chromosome as a string
     */
    public function get_chromosome_string()
    {
        return implode(",", $this->chromosome);
    }
}