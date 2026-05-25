<!-- TIPOS DE MANTENIMIENTOS -->
CREATE TABLE maintenance_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,
    code VARCHAR(50) NULL,

    type ENUM(
        'preventivo',
        'correctivo',
        'emergencia',
        'inspeccion'
    ) NOT NULL DEFAULT 'preventivo',

    description TEXT NULL,

    frequency_type ENUM(
        'km',
        'dias',
        'ambos',
        'manual'
    ) NOT NULL DEFAULT 'manual',

    frequency_km INT NULL,
    frequency_days INT NULL,

    estimated_duration_hours DECIMAL(8,2) NULL,
    estimated_cost DECIMAL(12,2) NULL,

    priority ENUM(
        'baja',
        'media',
        'alta',
        'urgente'
    ) NOT NULL DEFAULT 'media',

    requires_unit_stop TINYINT(1) NOT NULL DEFAULT 0,
    requires_evidence TINYINT(1) NOT NULL DEFAULT 0,

    active TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);