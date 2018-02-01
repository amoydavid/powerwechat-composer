<p align="center">
    <h1 align="center">PowerWeChat Composer Plugin</h1>
</p>


This is the composer plugin for [PowerWeChat](https://github.com/amoydavid/PowerWeChat).


Usage
---

Set the `type` to be `powerwechat-extension` in your package composer.json file:

```json
{
    "name": "your/package",
    "type": "powerwechat-extension"
}
```

Specify server observer classes in the extra section:

```json
{
    "name": "your/package",
    "type": "powerwechat-extension",
    "extra": {
        "observers": [
            "Acme\\Observers\\Handler"
        ]
    }
}
```


Thanks to easywechat-composer.
