<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    /**
     * Disable timestamps if not needed
     * Remove this if you want created_at and updated_at
     */
    public $timestamps = true;

    /**
     * Get setting value by key with cache
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set or update setting value
     * 
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @return Setting
     */
    public static function set($key, $value, $type = 'text', $group = 'general')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group
            ]
        );

        // Clear specific cache
        Cache::forget("setting_{$key}");
        
        // Clear grouped cache
        Cache::forget('settings_for_view');
        Cache::forget("settings_group_{$group}");
        
        return $setting;
    }

    /**
     * Get all settings grouped by group
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function getAllGrouped()
    {
        return Cache::remember('settings_all_grouped', 3600, function () {
            return self::all()->groupBy('group');
        });
    }

    /**
     * Get settings by specific group
     * 
     * @param string $group
     * @return \Illuminate\Support\Collection
     */
    public static function getByGroup($group)
    {
        return Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
            return self::where('group', $group)->get();
        });
    }

    /**
     * Clear all settings cache
     * 
     * @return void
     */
    public static function clearCache()
    {
        $keys = self::pluck('key');
        
        // Clear individual key caches
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
        
        // Clear grouped caches
        Cache::forget('settings_for_view');
        Cache::forget('settings_all_grouped');
        
        // Clear group specific caches
        $groups = self::pluck('group')->unique();
        foreach ($groups as $group) {
            Cache::forget("settings_group_{$group}");
        }
    }

    /**
     * Get settings as array for easy access in views
     * 
     * @return array
     */
    public static function getForView()
    {
        return Cache::remember('settings_for_view', 3600, function () {
            return self::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get multiple settings at once
     * 
     * @param array $keys
     * @param mixed $default
     * @return array
     */
    public static function getMultiple(array $keys, $default = null)
    {
        $results = [];
        
        foreach ($keys as $key) {
            $results[$key] = self::get($key, $default);
        }
        
        return $results;
    }

    /**
     * Check if a setting exists
     * 
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return Cache::remember("setting_exists_{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->exists();
        });
    }

    /**
     * Delete a setting by key
     * 
     * @param string $key
     * @return bool
     */
    public static function remove($key)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            Cache::forget("setting_{$key}");
            Cache::forget('settings_for_view');
            Cache::forget('settings_all_grouped');
            Cache::forget("settings_group_{$setting->group}");
            
            return $setting->delete();
        }
        
        return false;
    }

    /**
     * Get setting with type casting
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getTyped($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        // Type casting based on type field
        switch ($setting->type) {
            case 'boolean':
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            
            case 'integer':
            case 'number':
                return (int) $setting->value;
            
            case 'float':
            case 'decimal':
                return (float) $setting->value;
            
            case 'array':
            case 'json':
                return json_decode($setting->value, true);
            
            default:
                return $setting->value;
        }
    }

    /**
     * Batch update multiple settings
     * 
     * @param array $settings ['key' => 'value', ...]
     * @param string $group
     * @return bool
     */
    public static function setMultiple(array $settings, $group = 'general')
    {
        foreach ($settings as $key => $value) {
            // Detect type automatically
            $type = 'text';
            
            if (is_bool($value)) {
                $type = 'boolean';
                $value = $value ? '1' : '0';
            } elseif (is_numeric($value)) {
                $type = is_int($value) ? 'integer' : 'float';
            } elseif (is_array($value)) {
                $type = 'array';
                $value = json_encode($value);
            }
            
            self::set($key, $value, $type, $group);
        }
        
        return true;
    }

    /**
     * Get all settings in a specific format for frontend
     * 
     * @return array
     */
    public static function getAllFormatted()
    {
        return Cache::remember('settings_formatted', 3600, function () {
            $settings = self::all();
            $formatted = [];
            
            foreach ($settings as $setting) {
                $formatted[$setting->key] = [
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'group' => $setting->group,
                ];
            }
            
            return $formatted;
        });
    }

    /**
     * Get settings for specific groups
     * 
     * @param array $groups
     * @return \Illuminate\Support\Collection
     */
    public static function getByGroups(array $groups)
    {
        $cacheKey = 'settings_groups_' . implode('_', $groups);
        
        return Cache::remember($cacheKey, 3600, function () use ($groups) {
            return self::whereIn('group', $groups)->get();
        });
    }

    /**
     * Export settings as JSON
     * 
     * @param string|null $group
     * @return string
     */
    public static function exportAsJson($group = null)
    {
        $query = self::query();
        
        if ($group) {
            $query->where('group', $group);
        }
        
        return $query->get()->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Import settings from array
     * 
     * @param array $settings
     * @param bool $overwrite
     * @return int Number of settings imported
     */
    public static function importFromArray(array $settings, $overwrite = false)
    {
        $imported = 0;
        
        foreach ($settings as $setting) {
            if (!isset($setting['key']) || !isset($setting['value'])) {
                continue;
            }
            
            // Check if exists
            $exists = self::where('key', $setting['key'])->exists();
            
            if (!$exists || $overwrite) {
                self::set(
                    $setting['key'],
                    $setting['value'],
                    $setting['type'] ?? 'text',
                    $setting['group'] ?? 'general'
                );
                $imported++;
            }
        }
        
        return $imported;
    }

    /**
     * Search settings by key or value
     * 
     * @param string $query
     * @return \Illuminate\Support\Collection
     */
    public static function search($query)
    {
        return self::where('key', 'like', "%{$query}%")
                   ->orWhere('value', 'like', "%{$query}%")
                   ->get();
    }

    /**
     * Boot method to clear cache on model events
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when model is updated or deleted
        static::saved(function ($setting) {
            Cache::forget("setting_{$setting->key}");
            Cache::forget('settings_for_view');
            Cache::forget('settings_all_grouped');
            Cache::forget('settings_formatted');
            Cache::forget("settings_group_{$setting->group}");
            Cache::forget("setting_exists_{$setting->key}");
        });

        static::deleted(function ($setting) {
            Cache::forget("setting_{$setting->key}");
            Cache::forget('settings_for_view');
            Cache::forget('settings_all_grouped');
            Cache::forget('settings_formatted');
            Cache::forget("settings_group_{$setting->group}");
            Cache::forget("setting_exists_{$setting->key}");
        });
    }
}