<?php

namespace Adilarry\GALib;

class GeneticAlgorithm
{
    /**
     * This is the number of individuals in the population
     *
     * @var int
     */
    private $population_size;

    /**
     * This is the probability that a specific gene in a solution’s
     * chromosome will be mutated
     *
     * @var double
     */
    private $mutation_rate;

    /**
     * This is the frequency in which crossover is applied
     *
     * @var double
     */
    private $crossover_rate;

    /**
     * This represents the number of individuals to be
     * considered as elite and skipped during crossover
     *
     * @var integer
     */
    private $elitism_count;

    /**
     * Size of the tournament
     *
     * @var int
     */
    private $tournament_size;

    /**
     * Temperature for simulated annealing
     *
     * @var int
     */
    private $temperature;

    /**
     * Cooling rate for simulated annealing
     *
     * @var int
     */
    private $cooling_rate;

    /**
     * Algorithm for crossover operation
     *
     * @var string
     */
    private $crossover_type;

    /**
     * Algorithm for mutation operation
     *
     * @var string
     */
    private $mutation_type;

    /**
     * Class for implementation of given algorithm
     *
     * @var mixed
     */
    private $algorithm;

    /**
     * Whether to use an adaptive mutation rate or not
     *
     * @var boolean
     */
    private $use_adaptive_mutation;

    /**
     * Create a new instance of this class
     *
     * @param array $config Array of settings for setting up the algorithm
     * @param mixed $algorithm Problem's algorithm implementation object
     */
    public function __construct($config, $algorithm)
    {
        $this->population_size = $config->get_population_size();
        $this->mutation_rate = $config->get_mutation_rate();
        $this->crossover_rate = $config->get_crossover_rate();
        $this->elitism_count = $config->get_elitism_count();
        $this->tournament_size = $config->get_tournament_size();
        $this->temperature = $config->get_temperature();
        $this->cooling_rate = $config->get_cooling_rate();
        $this->crossover_type = $config->get_crossover_type();
        $this->mutation_type = $config->get_mutation_type();
        $this->use_adaptive_mutation = $config->get_adaptive_mutation();

        $this->algorithm = $algorithm;
    }

    /**
     * Initialize a population
     *
     * @param mixed $algorithm Client algorithm instance
     */
    public function init_population($algorithm)
    {
        $population = new Population($this->population_size, $algorithm);

        return $population;
    }

    /**
     * Get the temperature of the system for simulated annealing
     */
    public function get_temperature()
    {
        return $this->temperature;
    }

    /**
     * Cool the temperature of the system
     *
     */
    public function cool_temperature()
    {
        $this->temperature *= (1 - $this->cooling_rate);
    }

    /**
     * Calculate the fitness of a given individual
     *
     * @param Individual $individual The individual
     * @param mixed $algorithm An instance of client algorithm
     * @return double The fitness of the individual
     */
    public function calculate_fitness($individual, $algorithm)
    {
        $fitness = $algorithm->calculate_fitness($individual);

        $individual->set_fitness($fitness);
        return $fitness;
    }

    /**
     * Evaluate a given population
     *
     * @param Population $population The population to evaluate
     * @param mixed $algorithm An instance of client algorithm
     */
    public function evaluate_population($population, $algorithm)
    {
        $population_fitness = 0;

        $individuals = $population->get_individuals();

        foreach ($individuals as $individual) {
            $population_fitness += $this->calculate_fitness($individual, $algorithm);
        }

        $population->set_fitness($population_fitness);
    }

    /**
     * Select a parent for crossover purposes
     *
     * @param Population A population
     */
    public function select_parent($population)
    {
        return $this->tournament_select($population);
    }

    /**
     * Select a parent from a population to be used in a crossover
     * using the tournament selection technique
     *
     * @param Population $population The population
     * @return Individual The selected parent
     */
    public function tournament_select($population)
    {
        $tournament = new Population($this->tournament_size, $this->algorithm);

        $population->shuffle();

        for ($i = 0; $i < $this->tournament_size; $i++) {
            $participant = $population->get_individual($i);
            $tournament->set_individual($i, $participant);
        }

        return $tournament->get_fittest(0);
    }

    /**
     * Perform crossover among the individuals of a population
     *
     * @param Population $population The population
     */
    public function crossover_population($population)
    {
        if ($this->crossover_type == CrossoverTypes::SINGLE_POINT) {
            return $this->single_point_crossover($population);
        } elseif ($this->crossover_type == CrossoverTypes::MULTI_POINT) {
            return $this->multi_point_crossover($population);
        } elseif ($this->crossover_type == CrossoverTypes::UNIFORM) {
            return $this->uniform_crossover($population);
        } else {
            throw new Exception('Invalid crossover type');
        }
    }

    /**
     * Perform mutation on individuals of a population
     *
     * @param Population $population The population
     */
    public function mutate_population($population)
    {
        if ($this->mutation_type == MutationTypes::BIT_FLIP) {
            return $this->bit_flip_mutation($population);
        } else if ($this->mutation_type == MutationTypes::RANDOM_RESETTING) {
            return $this->random_reset_mutation($population);
        }
    }

    /**
     * Perform crossover on members of a population using the single point
     * crossover algorithm
     *
     * @param Population $population The population
     * @return Population A new population
     */
    public function single_point_crossover($population)
    {
        print "\nUsing single-point crossover\n";
        $new_population = new Population($this->population_size, $this->algorithm);

        for ($i = 0; $i < $population->size(); $i++) {
            $parent_A = $population->get_fittest($i);

            $random = mt_rand() / mt_getrandmax();

            if (($this->crossover_rate > $random) && ($i > $this->elitism_count)) {
                $offspring = new Individual($this->algorithm);

                $parent_B = $this->select_parent(clone $population);
                print "\nParent A: " . $parent_A . "\n";
                print "\nParent B: " . $parent_B . "\n";

                $length = $parent_B->get_chromosome_length();

                $swap_point = mt_rand(0, $length);
                print "\nSwap point: " . $swap_point . "\n";

                for ($j = 0; $j < $length; $j++) {
                    if ($j < $swap_point) {
                        $offspring->set_gene($j, $parent_A->get_gene($j));
                    } else {
                        $offspring->set_gene($j, $parent_B->get_gene($j));
                    }
                }

                print "\nOffspring: " . $offspring . "\n";

                $new_population->set_individual($i, $offspring);
            } else {
                $new_population->set_individual($i, $parent_A);
            }
        }

        return $new_population;
    }

    /**
     * Perform crossover on members of a population using the multi-point crossover
     * algorithm
     *
     * @param Population $population The population
     * @return Population A new population
     */
    public function multi_point_crossover($population)
    {
        print "\nUsing multi-point crossover\n";
        $new_population = new Population($this->population_size, $this->algorithm);

        for ($i = 0; $i < $population->size(); $i++) {
            $parent_A = $population->get_fittest($i);

            $random = mt_rand() / mt_getrandmax();

            if (($this->crossover_rate > $random) && ($i > $this->elitism_count)) {
                $offspring = new Individual($this->algorithm);

                $parent_B = $this->select_parent(clone $population);
                print "\nParent A: " . $parent_A . "\n";
                print "\nParent B: " . $parent_B . "\n";

                $length = $parent_B->get_chromosome_length();

                $swap_point_a = mt_rand($length / 4, $length / 2);
                $swap_point_b = mt_rand(($length / 2) + 1, $length);

                print "Swap point 1: " . $swap_point_a . "\n";
                print "Swap point 2: " . $swap_point_b . "\n";

                for ($j = 0; $j < $parent_A->get_chromosome_length(); $j++) {
                    if ($j < $swap_point_a || $j > $swap_point_b) {
                        $offspring->set_gene($j, $parent_A->get_gene($j));
                    } else {
                        $offspring->set_gene($j, $parent_B->get_gene($j));
                    }
                }

                print "\nOffspring: " . $offspring . "\n";

                $new_population->set_individual($i, $offspring);
            } else {
                $new_population->set_individual($i, $parent_A);
            }
        }

        return $new_population;
    }

    /**
     * Perform crossover on members of a population using the uniform crossover
     * method
     *
     * @param Population $population The population
     * @return Population A new population
     */
    public function uniform_crossover($population)
    {
        //print "\nUsing uniform crossover\n";
        $new_population = new Population($this->population_size, $this->algorithm);

        for ($i = 0; $i < $population->size(); $i++) {
            $parent_A = $population->get_fittest($i);

            $crossover_chance = mt_rand() / mt_getrandmax();

            if (($this->crossover_rate > $crossover_chance) && ($i > $this->elitism_count)) {
                $offspring = new Individual($this->algorithm);

                $parent_B = $this->select_parent(clone $population);
                //print "\nParent A: " . $parent_A . "\n";
                //print "\nParent B: " . $parent_B . "\n";

                $length = $parent_B->get_chromosome_length();

                for ($j = 0; $j < $length; $j++) {
                    $parent_chance = mt_rand() / mt_getrandmax();

                    if (0.5 > $parent_chance) {
                        $offspring->set_gene($j, $parent_A->get_gene($j));
                    } else {
                        $offspring->set_gene($j, $parent_B->get_gene($j));
                    }
                }

                //print "\nOffspring: " . $offspring . "\n";

                $new_population->set_individual($i, $offspring);
            } else {
                $new_population->set_individual($i, $parent_A);
            }
        }

        return $new_population;
    }

    /**
     * Perform a bit flip mutation on individuals of a population
     *
     * @param Population $population The population
     * @return Population A new population
     */
    public function bit_flip_mutation($population)
    {
        $new_population = new Population($this->population_size, $this->algorithm);
        $best_fitness = $population->get_fittest(0)->get_fitness();

        for ($i = 0; $i < $population->size(); $i++) {
            $individual = $population->get_fittest($i);

            if ($this->use_adaptive_mutation) {
                $mutation_rate = $this->mutation_rate;

                if ($individual->get_fitness() > $population->get_avg_fitness()) {
                    $fitness_delta1 = $best_fitness - $individual->get_fitness();
                    $fitness_delta2 = $best_fitness - $population->get_avg_fitness();
                    $mutation_rate = ($fitness_delta1 / $fitness_delta2) * $this->mutation_rate;
                }

            } else {
                $mutation_rate = $this->mutation_rate;
            }

            if ($i > $this->elitism_count) {
                for ($j = 0; $j < $individual->get_chromosome_length(); $j++) {
                    $random = mt_rand() / mt_getrandmax();

                    if ($mutation_rate > $random) {
                        $gene = $individual->get_gene($j);

                        if ($gene == '0') {
                            $gene = '1';
                        } elseif ($gene == '1') {
                            $gene = '0';
                        }

                        $individual->set_gene($j, $gene);
                    }
                }
            }

            $new_population->set_individual($i, $individual);
        }

        return $new_population;
    }

    /**
     * Perform a random reset mutation on individuals of a population
     *
     * @param Population $population The population
     * @return Population A new population
     */
    public function random_reset_mutation($population)
    {
        print "\nUsing random reset mutation\n";

        $new_population = new Population($this->population_size, $this->algorithm);
        $best_fitness = $population->get_fittest(0)->get_fitness();

        for ($i = 0; $i < $population->size(); $i++) {
            $individual = $population->get_fittest($i);
            $random_individual = new Individual($this->algorithm);

            if ($this->use_adaptive_mutation) {
                $mutation_rate = $this->mutation_rate;

                if ($individual->get_fitness() > $population->get_avg_fitness()) {
                    $fitness_delta1 = $best_fitness - $individual->get_fitness();
                    $fitness_delta2 = $best_fitness - $population->get_avg_fitness();
                    $mutation_rate = ($fitness_delta1 / $fitness_delta2) * $this->mutation_rate;
                }
            } else {
                $mutation_rate = $this->mutation_rate;
            }

            if ($i > $this->elitism_count) {
                for ($j = 0; $j < $individual->get_chromosome_length(); $j++) {
                    $random = mt_rand() / mt_getrandmax();

                    if ($mutation_rate > $random) {
                        $individual->set_gene($j, $random_individual->get_gene($j));
                    }
                }
            }

            $new_population->set_individual($i, $individual);
        }

        return $new_population;
    }
}