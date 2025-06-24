<?php
namespace puzzlethings\src\object;

class Status implements \JsonSerializable {
    private int $id;
    private ?string $name;

    public function __construct(int $id, ?string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public static function of($res): Status {
        return new Status($res['statusid'], $res['statusdesc']);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}