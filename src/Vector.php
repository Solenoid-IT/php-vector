<?php



namespace Solenoid\Vector;



class Vector
{
    private array  $array;
    private string $separator;

    private int    $current_index;



    # Returns [array]
    private static function _expand (array $array, string $separator)
    {
        // (Setting the value)
        $result = [];

        foreach ($array as $k => $v)
        {// Processing each entry
            if ( is_array( $v ) )
            {// Match OK
                // (Expanding the array)
                $v = self::_expand( $v, $separator );
            }



            foreach (array_reverse( explode( $separator, $k ) ) as $k)
            {// Processing each entry
                // (Getting the value)
                $v = [ $k => $v ];
            }

            // (Getting the value)
            $result = array_merge_recursive( $result, $v );
        }



        // Returning the value
        return $result;
    }



    # Returns [self]
    public function __construct (array $array, ?string $separator = '.')
    {
        // (Getting the values)
        $this->array     = $array;
        $this->separator = $separator;



        // (Setting the value)
        $this->current_index = -1;
    }

    # Returns [Vector]
    public static function create (array $array, ?string $separator = '.')
    {
        // Returning the value
        return new Vector( $array, $separator );
    }



    # Returns [int]
    public function count ()
    {
        // Returning the value
        return count( $this->array );
    }

    # Returns [bool]
    public function is_empty ()
    {
        // Returning the value
        return $this->count() === 0;
    }




    # Returns [mixed]
    public function fetch_head ()
    {
        // Returning the value
        return $this->array[ 0 ];
    }

    # Returns [mixed]
    public function fetch_tail ()
    {
        // Returning the value
        return $this->array[ $this->count( $this->array ) - 1 ];
    }



    # Returns [mixed|null]
    public function fetch_next ()
    {
        // (Incrementing the value)
        $this->current_index += 1;

        if ( $this->current_index === count( $this->array ) - 1 )
        {// Match OK
            // Returning the value
            return null;
        }



        // Returning the value
        return $this->array[ $this->current_index ];
    }



    # Returns [Vector]
    public function compress ()
    {
        // (Setting the value)
        $result = [];

        

        // (Creating a RecursiveIteratorIterator)
        $rii = new \RecursiveIteratorIterator( new \RecursiveArrayIterator( $this->array ) );

        foreach ($rii as $leaf_value)
        {// Processing each entry
            // (Setting the value)
            $keys = [];

            foreach (range( 0, $rii->getDepth() ) as $depth)
            {// Processing each entry
                // (Appending the value)
                $keys[] = $rii->getSubIterator( $depth )->key();
            }



            // (Getting the value)
            $result[ implode( $this->separator, $keys ) ] = $leaf_value;
        }



        // Returning the value
        return Vector::create( $result );
    }

    # Returns [Vector]
    public function expand ()
    {
        // Returning the value
        return Vector::create( self::_expand( $this->array, $this->separator ) );
    }



    # Returns [bool]
    public function is_sequential ()
    {
        // Returning the value
        return array_keys( $this->array ) === range( 0, count( $this->array ) - 1 );
    }
    
    # Returns [bool]
    public function is_associative ()
    {
        // Returning the value
        return !$this->is_sequential();
    }



    # Returns [Vector]
    public function transpose ()
    {
        /*

        #debug
        $matrix =
        [
            0 =>
            [
                'rosso',
                'verde',
                'blu',
                'viola'
            ],
            1 =>
            [
                'Mario',
                'Giordano',
                'Fabio',
                'Andrea'
            ],
            2 =>
            [
                '2023-01-05',
                '2022-03-04',
                '2020-09-07',
                '2019-01-02'
            ]
        ]
        ;

        */



        // (Getting the value)
        $matrix = $this->array;



        // (Setting the value)
        $transposed_matrix = [];

        for ($i = 0; $i < count( $matrix[0] ); $i++)
        {// Iterating each index
            foreach ($matrix as $j => $v)
            {// Processing each entry
                // (Getting the value)
                $transposed_matrix[$i][$j] = $v[$i];
            }
        }



        // Returning the value
        return Vector::create( $transposed_matrix );
    }

    # Returns [Vector]
    public function build_record (array $schema)
    {
        // (Setting the value)
        $record = [];



        foreach ($schema as $k => $v)
        {// Processing each entry
            // (Getting the value)
            $record[$v] = $this->array[$k];
        }



        // Returning the value
        return Vector::create( $record );
    }

    # Returns [Vector]
    public function swap ()
    {
        // (Setting the value)
        $swapped_kv_list = [];

        foreach ($this->array as $k => $v)
        {// Processing each entry
            // (Getting the value)
            $swapped_kv_list[$v][] = $k;
        }



        foreach ($swapped_kv_list as $k => $v)
        {// Processing each entry
            if ( count( $swapped_kv_list[$k] ) === 1 )
            {// Match OK
                // (Getting the value)
                $swapped_kv_list[$k] = $swapped_kv_list[$k][0];
            }
        }



        // Returning the value
        return Vector::create( $swapped_kv_list );
    }



    # Returns [array]
    public function to_array ()
    {
        // Returning the value
        return $this->array;
    }
}



?>