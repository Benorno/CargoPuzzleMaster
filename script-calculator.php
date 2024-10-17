<?php
class ShippingContainer {
    public $name;
    public $width;
    public $height;
    public $length;

    public function __construct($name, $width, $height, $length) {
        $this->name = $name;
        // convert cm to meters
        $this->width = $width / 100;
        $this->height = $height / 100;
        $this->length = $length / 100;
    }

    public function getVolume() {
        return $this->width * $this->height * $this->length;
    }
}

class Transport {
    public $contents;
    public $width;
    public $height;
    public $length;

    public function __construct($contents, $width, $height, $length) {
        $this->contents = $contents;
        // convert cm to meters
        $this->width = $width / 100;
        $this->height = $height / 100;
        $this->length = $length / 100;
    }

    public function getVolume() {
        return $this->width * $this->height * $this->length;
    }
}

class ShippingCalculator {
    public function calculateContainerVolume($container, $quantity) {
        return round($container->getVolume() * $quantity, 2);
    }

    public function calculateTransportVolume($transport, $quantity) {
        return round($transport->getVolume() * $quantity, 2);
    }

    public function canItemsFit($containerVolume, $transportVolume) {
        return $transportVolume <= $containerVolume;
    }

    // Calculate how many containers are needed for all transports
    public function calculateContainersForTransports($containers, $transports, $quantities) {
        $results = [];

        foreach ($transports as $transportKey => $transport) {
            $transportVolume = $this->calculateTransportVolume($transport, $quantities[$transportKey]);
            $containerAssigned = false;

            foreach ($containers as $containerKey => $container) {
                $containerVolume = $this->calculateContainerVolume($container, 1);

                if ($this->canItemsFit($containerVolume, $transportVolume)) {
                    $results[] = "The items from " . $transport->contents . " fit into the " . $container->name . " shipping container.
                    Total volume of items: " . $transportVolume . " cubic meters,
                    Container volume: " . $containerVolume . " cubic meters.";
                    $containerAssigned = true;
                    break;
                }
            }

            if (!$containerAssigned) {
                $results[] = "The items from " . $transport->contents . " do not fit into any of the selected shipping containers.
                Total volume of items: " . $transportVolume . " cubic meters.";
            }
        }

        return $results;
    }
}

// Shipping Container Objects
$containers = array(
    "10ft" => new ShippingContainer("10ft Standard Dry Container", 234.8, 238.44, 279.4),
    "40ft" => new ShippingContainer("40ft Standard Dry Container", 234.8, 238.44, 1203.1),
);

// Transport Objects
$transports = array(
    "transport1" => new Transport("Kid toys", 78, 79, 93),
    "transport2" => new Transport("Men's shoes", 30, 60, 90),
    "transport3" => new Transport("Womens's shoes", 75, 100, 200),
    "transport4" => new Transport("Animal products", 80, 100, 200),
    "transport5" => new Transport("Small electronics", 60, 80, 150),
);

// Quantities for each transport
$transportQuantities = array(
    "transport1" => 27,
    "transport2" => 24,
    "transport3" => 33,
    "transport4" => 10,
    "transport5" => 25
);


// Calculate
$calculator = new ShippingCalculator();
$results = $calculator->calculateContainersForTransports($containers, $transports, $transportQuantities);

// Output
foreach ($results as $result) {
    echo $result . "\n\n";
}
