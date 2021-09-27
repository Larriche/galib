<?php

namespace Adilarry\GALib;

class GeneticAlgorithmConfig
{
    /**
     * Size of population of individuals to be created
     *
     * @var int
     */
    private $population_size;

    /**
     * Mutation rate
     *
     * @var double
     */
    private $mutation_rate;

    /**
     * Crossover rate
     *
     * @var double
     */
    private $crossover_rate;

    /**
     * The elitism count for a population's individuals
     *
     * @var int
     */
    private $elitism_count;

    /**
     * Number of tournament participants for implementations using
     * tournament selection
     *
     * @var int
     */
    private $tournament_size;

    /**
     * Initial temperature for implementations using simulated annealing
     *
     * @var double
     */
    private $temperature;

    /**
     * Cooling rate for simulated annealing implementations
     *
     * @var double
     */
    private $cooling_rate;

    /**
     * Choice for algorithm to use in crossover operations
     *
     * @var string
     */
    private $crossover_type;

    /**
     * Choice for algorithm to use in mutation operations
     *
     * @var string
     */
    private $mutation_type;

    /**
     * Determines whether to use adaptive mutation
     * or to use a fixed mutation rate
     *
     * @var boolean
     */
    private $use_adaptive_mutation;

    /**
     * Create a new instance of this class
     *
     */
    public function __construct()
    {
        // Set arbitrary initial values
        $this->population_size = 100;
        $this->elitism_count = 2;
        $this->tournament_size = 10;

        $this->mutation_rate = 0.01;
        $this->crossover_rate = 0.9;
        $this->cooling_rate = 0.001;
        $this->temperature = 1.0;

        $this->crossover_type = CrossoverTypes::UNIFORM;
        $this->mutation_type = MutationTypes::BIT_FLIP;

        $this->use_adaptive_mutation = false;
    }

    /**
     * Set the population size param
     *
     * @param int $population_size Population size
     */
    public function set_population_size($population_size)
    {
        $this->population_size = $population_size;
    }

    /**
     * Get population size config
     *
     * @return int Population size
     */
    public function get_population_size()
    {
        return $this->population_size;
    }

    /**
     * Set the elitism count config
     *
     * @param int $count Elitism count
     */
    public function set_elitism_count($count)
    {
        $this->elitism_count = $count;
    }

    /**
     * Get the elitism count config
     *
     * @return int Elitism count
     */
    public function get_elitism_count()
    {
        return $this->elitism_count;
    }

    /**
     * Set the tournament size config
     *
     * @param int $size Size of tournament
     */
    public function set_tournament_size($size)
    {
        $this->tournament_size = $size;
    }

    /**
     * Get the tournament size
     *
     * @return int Tournament size
     */
    public function get_tournament_size()
    {
        return $this->tournament_size;
    }

    /**
     * Set the mutation rate
     *
     * @param double $rate The mutation rate
     */
    public function set_mutation_rate($rate)
    {
        $this->mutation_rate = $rate;
    }

    /**
     * Get the mutation rate config
     *
     * @return double The mutation rate
     */
    public function get_mutation_rate()
    {
        return $this->mutation_rate;
    }

    /**
     * Set the crossover rate
     *
     * @param double $rate The crossover rate
     */
    public function set_crossover_rate($rate)
    {
        $this->crossover_rate = $rate;
    }

    /**
     * Get the crossover rate
     *
     * @return double The crossover rate
     */
    public function get_crossover_rate()
    {
        return $this->crossover_rate;
    }

    /**
     * Set the cooling rate
     *
     * @param double $rate The cooling rate
     */
    public function set_cooling_rate($rate)
    {
        $this->cooling_rate = $rate;
    }

    /**
     * Get the cooling rate
     *
     * @return double The cooling rate
     */
    public function get_cooling_rate()
    {
        return $this->cooling_rate;
    }

    /**
     * Set the temperature
     *
     * @param double $temperature The temperatur
     */
    public function set_temperature($temperature)
    {
        $this->temperature = $temperature;
    }

    /**
     * Get the temperature
     *
     * @return double The system's temperature
     */
    public function get_temperature()
    {
        return $this->temperature;
    }

    /**
     * Set crossover algorithm type
     *
     * @param string $type Crossover algorithm type
     */
    public function set_crossover_type($type)
    {
        $this->crossover_type = $type;
    }

    /**
     * Get the crossover type
     *
     * @return string The crossover type
     */
    public function get_crossover_type()
    {
        return $this->crossover_type;
    }

    /**
     * Set the mutation type
     *
     * @param string $type Mutation type
     */
    public function set_mutation_type($type)
    {
        $this->mutation_type = $type;
    }

    /**
     * Get the mutation type
     *
     * @return string Mutation type
     */
    public function get_mutation_type()
    {
        return $this->mutation_type;
    }

    /**
     * Set whether to use adaptive mutation or not
     *
     * @param boolean $use Whether to use adaptive mutation
     */
    public function set_adaptive_mutation($use)
    {
        $this->use_adaptive_mutation = $use;
    }

    /**
     * Get value for adaptive mutation flag
     *
     * @return boolean Value of adaptive mutation flag
     */
    public function get_adaptive_mutation()
    {
        return $this->use_adaptive_mutation;
    }
}