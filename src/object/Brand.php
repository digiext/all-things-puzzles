<?php
namespace puzzlethings\src\object;

class Brand implements \JsonSerializable {
    private ?int $id;
    private ?string $name;

    public function __construct(?int $id, ?string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public static function of(mixed $res): Brand {
        return new Brand($res["brandid"], $res["brandname"]);
    }

    public function getId(): ?int {
        return $this->id;
    }
    public function getName(): ?string {
        return $this->name;
    }

    public function jsonSerialize(): mixed {
        return [
            "id" => $this->id,
            "name" => $this->name
        ];
    }
}